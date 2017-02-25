<?php 
	$redis=new Redis();
	$redis->pconnect('127.0.0.1',9005);
//	for($i=0;$i<100000;$i++){
	$r=$redis->get('{"f":"func","m":"call","p":["hello"," ","world","!!!"]}');
echo $r;
//}
//	var_dump($r);

