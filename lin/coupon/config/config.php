<?php
    $config = array(
         'db' => array(                                   //数据库配置文件
             'type' => 'mysql',
             'port' => '',
             'host' => '101.37.67.41',
             'dbName' => 'jiayouxianhuo',
             'user' => 'root',
             'password' => 'Lzmyoyo@163.com',
             'isLong' => false,
             'charset' => 'utf8',
             'tableFlx' => 'jia_'
         ),
        /*
         'db' => array(                                   //数据库配置文件
             'type' => 'mysql',
             'port' => '3306',
             'host' => '192.168.33.15',
             'dbName' => 'coupon',
             'user' => 'root',
             'password' => 'root',
             'isLong' => false,
             'charset' => 'utf8',
             'tableFlx' => 'guanjia_'
         ),
*/
        'fileFix' => 'php',                                 //视图后缀，不能修改
        'viewPath' => APPPATH . 'views/',                   //视图地址
        'log' => APPPATH . 'log.txt',                       //日志地址
        'newPath' => array('admin','api'),  //模块控制器
        'siteUrl' => 'http://101.37.67.41',
        'adminFiltrateAction' => array(                     //后台访问时过滤这些控制器方法可以不用登录
            'indexController' => array(
                'login',
                'loginIn',
                'loginOut',
                'index'
            ),
            'showCategoryController' => array(
                'ajaxGetChildCategory'
            )
        ),
        'sms' => [
            'sdkappid' => '1400080224',
            'appkey' => 'be77e1bfd02bee86dfaae07257a5dc8e',
            'smsMessage' => [
                'sendUrl' => 'https://yun.tim.qq.com/v5/tlssmssvr/sendsms',
                'template' => [
                    'login' => 103852,
                    'register' => 103849,
                    'join' => 103867,
                ]
            ],
            'sign' => '智乐淘'
        ],
        'aliwappay' => array(
            'partner' => '2088702490911223',
            'seller_email' => '18267140729',
            'key' => '86fimcp2p4m09r8mz7p04xzfjhalp8ef',
            'private_key_path' => APPPATH.'aliwappay/key/rsa_private_key.pem',
            'ali_public_key_path' => APPPATH.'aliwappay/key/alipay_public_key.pem',
            'sign_type' => '0001',
            'input_charset' => 'utf-8',
            'cacert' => APPPATH.'aliwappay/key/cacert.pem',
            'transport' => 'http'
        ),
        'wxConfig' => [
            'appId' => 'wx3de89ce51f23023e',
            'secret' => '0b72e95b3bd312f10ad715247cf72786',
            'getSessionKeyUrl' => 'https://api.weixin.qq.com/sns/jscode2session'
        ],
        'apiUrl' => 'http://101.37.67.41/api/',
    );
    include 'menu.php';
    return $config;
?>
