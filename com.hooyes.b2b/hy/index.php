<?php

require(dirname(__FILE__)."/"."global.php");


/**
*��ǩʹ��
**/
$ch_fid	= $ch_pagetype = 0;
$ch_module = $webdb[module_id]?$webdb[module_id]:99;	//ϵͳ�ض�ID����,ÿ��ϵͳ������ͬ
require(ROOT_PATH."inc/label_module.php");


require(ROOT_PATH."inc/head.php");
require(getTpl('index'));
require(ROOT_PATH."inc/foot.php");

?>