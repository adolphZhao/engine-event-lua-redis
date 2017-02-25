# engine-event_lua_redis

use for php-redis smart and high speed service
develop language C and embed language lua.

#Support persistent connections

#install

yum -y install libevent-devel  
yum -y install lua5.1-0-dev  
./configure  
make  

#usage

see test.php  
see script/func.lua  

script/func.lua
```lua
function call(str1,str2,str3,str4)
        return str1..str2..str3..str4;
end
```
test.php
```php
<?php
        $redis=new Redis();
        $redis->pconnect('127.0.0.1',9005);
        $r=$redis->get('{"f":"func","m":"call","p":["hello"," ","world","!!!"]}');
        var_dump($r);

```
