<?php 
	$redis=new Redis();
	$redis->pconnect('127.0.0.1',9005);
	$r=$redis->get('{"f":"func","m":"call","p":["hello"," ","world","!!!"]}');
	var_dump($r);

