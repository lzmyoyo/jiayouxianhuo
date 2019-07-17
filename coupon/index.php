<?php

//error_reporting(0);
ini_set( 'display_errors','On');
session_start();
//项目名称。
define('APPNAME','coupon');


//框架目录地址
//define('LINPATH','/Users/linzhimin/allItem/coupon/lin/');
define('LINPATH',$_SERVER['DOCUMENT_ROOT'].'/../lin/');

//项目路径
define('APPPATH',LINPATH.APPNAME.'/');

//项目静态内容路径。
define('STATICPATH',LINPATH.'../'.APPNAME.'/');

//项目初始加载文件
include LINPATH.'core/start.php';

//设置项目自动夹在目录。
start::setIncludes();
//注册自动加载类
spl_autoload_register(array('start','loadClass'));

define('STARTTIME',microtime(true));

/*
	userModel::test();
	userModel::what();
*/


/*
		$array = array(
			'a' => 'as df',
			'b' => 'as-0s'
		);

		echo http_build_query($array);


		$str = 'a=as+df&b=as-0s';
		echo '<pre>';
		parse_str($str,$strArr);
		print_r($strArr);
		exit;
*/

start::run();
?>
