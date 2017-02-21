<?php


use Exception;

/**
 * redis client
 * 兼容redis协议的客户端，不依赖第三方库
 * redis协议参考: https://redis.io/topics/protocol
 *
 * @author sh7ning
 * @since 2017-02-16
 * @example
 *
 * $redis = new Client('tcp://127.0.0.1:6379');
 * var_dump($redis->lpush('key1', 1, 2, 3333));
 * var_dump($redis->lrange('key1', 0, 100));
 */
class Client
{
    /**
     * @var resource 流
     */
    protected $resource = null;

    /**
     * @var string 如 'tcp://127.0.0.1:6379'
     */
    protected $address;

    /**
     * @var float 连接redis的超时时间配置
     */
    protected $timeout = 3.0;

    /**
     * @var float 读写redis的超时时间配置
     */
    protected $rwTimeout = 3.0;

    /**
     * @var int socket连接选项 STREAM_CLIENT_CONNECT
     */
    protected $flags = STREAM_CLIENT_CONNECT;

    public function __construct($address, $timeout = 3.0, $rwTimeout = 3.0)
    {
        $this->address = $address;
        $this->timeout = $timeout;
        $this->rwTimeout = $rwTimeout;
    }

    /**
     * 设置 socket 连接选项
     */
    public function setFlag($flags)
    {
        // $flags = STREAM_CLIENT_CONNECT;
        // $flags |= STREAM_CLIENT_ASYNC_CONNECT;  //async_connect 异步
        // $flags |= STREAM_CLIENT_PERSISTENT; //persistent 持久化连接

        $this->flags = $flags;
    }

    public function __call($commandID, $arguments)
    {
        $commandID = strtoupper($commandID);
        return $this->executeCommand(
            $this->createCommand($commandID, $arguments)
        );
    }

    /**
     * 执行裸命令
     *
     * @param string $command 命令
     * @param bool $withResponse 是否返回响应
     * @return array|int|resource|string
     */
    public function executeCommand($command, $withResponse = true)
    {
        $this->writeRequest($command);

        return $withResponse ? $this->readResponse() : $this->getResource();
    }

    protected function createStreamSocket()
    {
        if (! $resource = @stream_socket_client($this->address, $errNo, $errStr, $this->timeout, $this->flags)) {
            throw new Exception('error:[' . $errNo . '] ' . $errStr);
        }

        //读写超时处理设置
        $rwTimeout = (float) $this->rwTimeout;
        $rwTimeout = $rwTimeout > 0 ? $rwTimeout : -1;
        $timeoutSeconds = floor($rwTimeout);
        $timeoutUSeconds = ($rwTimeout - $timeoutSeconds) * 1000000;
        stream_set_timeout($resource, $timeoutSeconds, $timeoutUSeconds);

        return $resource;
    }

    protected function getResource()
    {
        if (! is_resource($this->resource)) {
            $this->resource = $this->createStreamSocket();
        }

        return $this->resource;
    }

    protected function createCommand($commandID, $arguments)
    {
        $reqLen = count($arguments) + 1;
        $cmdLen = strlen($commandID);

        $buffer = "*{$reqLen}\r\n\${$cmdLen}\r\n{$commandID}\r\n";

        foreach ($arguments as $argument) {
            $argLen = strlen($argument);
            $buffer .= "\${$argLen}\r\n{$argument}\r\n";
        }

        return $buffer;
    }

    protected function writeRequest($buffer)
    {
        while (($length = strlen($buffer)) > 0) {
            $written = @fwrite($this->getResource(), $buffer);

            if ($length === $written) {
                return;
            }

            if ($written === false || $written === 0) {
                throw new Exception('Error while writing bytes to the server.');
            }

            $buffer = substr($buffer, $written);
        }
    }

    protected function readResponse()
    {
        //从文件指针中读取一行
        $chunk = fgets($this->getResource());

        if ($chunk === false || $chunk === '') {
            throw new Exception('Error while reading line from the server.');
        }

        $prefix = $chunk[0];
        $payload = substr($chunk, 1, -2);

        switch ($prefix) {
        case '+':
            return $payload;

        case '$':
            $size = (int) $payload;

            if ($size === -1) {
                return null;
            }

            $bulkData = '';
            $bytesLeft = ($size += 2);

            do {
                $chunk = fread($this->getResource(), min($bytesLeft, 4096));

                if ($chunk === false || $chunk === '') {
                    throw new Exception('Error while reading bytes from the server.');
                }

                $bulkData .= $chunk;
                $bytesLeft = $size - strlen($bulkData);
            } while ($bytesLeft > 0);

            return substr($bulkData, 0, -2);

        case '*':
            $count = (int) $payload;

            if ($count === -1) {
                return null;
            }

            $multibulk = array();

            for ($i = 0; $i < $count; ++$i) {
                $multibulk[$i] = $this->readResponse();
            }

            return $multibulk;

        case ':':
            $integer = (int) $payload;
            return $integer == $payload ? $integer : $payload;

        case '-':
            return $payload;

        default:
            throw new Exception("Unknown response prefix: '$prefix'.");
        }
    }
}
