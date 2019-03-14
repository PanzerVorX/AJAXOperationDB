<?php
	//减小资源参数与使用资源的文件的耦合度：获取资源操作单独作为文件，其它需使用资源的文件引入该文件
	$pdo=new PDO('mysql:host=localhost;dbname=PXSCJ','root','');
	$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_ASSOC);
	$sql='set names utf8';
	$pdo->exec($sql)
?>