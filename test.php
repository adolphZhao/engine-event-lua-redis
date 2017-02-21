<?php
//include './Client.php';
$redis = new Redis('tcp://127.0.0.1:9005');
$redis->connect('127.0.0.1', 9005);
//var_dump($redis->set('{"f":"func","m":"call","p":"{aaa:11,bb:2}"',1));for($i=0;$i<100000;$i++)
for($i=0;$i<100000;$i++){
	echo $redis->get('{"f":"func","m":"call","p":"{test:11,count:'.$i.'}"}')."\r\n";
}
$redis->close();
