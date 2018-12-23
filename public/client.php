<?php

$mem=new Memcached();
$mem->addServer('127.0.0.1',11211);

/* 获取ip列表以及它的标记*/
do {
	$ips = $mem->get('system_list', null, Memcached::GET_EXTENDED);
	if ($mem->getResultCode() == Memcached::RES_NOTFOUND) {
		$ips[] = rand(100,200);
		$mem->add('system_list', $ips);
	} else {
		$data=$ips['value'];
		$data[] = rand(1000,2000);
		$cas=$ips['cas'];
		$mem->cas($cas, 'system_list', $data);
	}
} while ($mem->getResultCode() != Memcached::RES_SUCCESS);

$ips = $mem->get('system_list', null, Memcached::GET_EXTENDED);
var_dump($ips);

?>