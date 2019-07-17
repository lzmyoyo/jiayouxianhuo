<?php
/**
 * 路由规则配置，分静态路由和动态路由，static静态路由配置，change 动态路由配置
 */
return array(
    'static' => array(
        '/' => array(
            'type' => 'get',
            'uses' => 'indexController@chamberlainList'
        ),
    ),
    'change' => array(

        'p/product{string}-list{id}.html' => array(
            'type' => 'get',
            'uses' => 'IndexController@product'
        ),
        'shop/list-{string}-{id}.html' => array(
            'type' => 'get',
            'group' => 'mobile',
            'uses' => 'shopController@shopinfo'
        ),
		'filter/{string}/{string}' => array(
			'type' => 'get',
			'uses' => 'indexController@index'
		),
		'filter/{string}/{string}.html' => array(
			'type' => 'get',
			'uses' => 'indexController@index'
		),
		'filter/{string}' => array(
			'type' => 'get',
			'uses' => 'indexController@index'
		),
		'brand/{string}-{id}.html' => array(
			'type' => 'get',
			'uses' => 'indexController@brand'
		),
		'coupon/{string}-{id}.html' => array(
			'type' => 'get',
			'uses' => 'indexController@coupon'
		),

    )
);
?>