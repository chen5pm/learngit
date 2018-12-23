<?php

$mem=new Memcached();
$mem->addServer('127.0.0.1',11211);

/* 获取ip列表以及它的标记*/


//这是是memcached正确的cas 事务处理
/*do {
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
var_dump($ips);*/


do {
    // 获取ip列表以及它的标记 
    $ips = $mem->get('ip_block', null, $cas);
    // 如果列表不存在， 创建并进行一个原子添加（如果其他客户端已经添加， 这里就返回false）
    if ($mem->getResultCode() == Memcached::RES_NOTFOUND) {
        $ips[] = rand(100,200);
        $mem->add('ip_block', $ips);
    // 其他情况下，添加ip到列表中， 并以cas方式去存储， 这样当其他客户端修改过， 则返回false 
    } else { 
        $ips[] = rand(100,200);
        $mem->cas($cas, 'ip_block', $ips);
    }   
} while ($mem->getResultCode() != Memcached::RES_SUCCESS);
  $ips = $mem->get('ip_block', null, $cas);
var_dump($ips);

?>