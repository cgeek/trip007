<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'旅游特价情报站',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'miaomiao',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
		),
		'admin',
	),

	// application components
	'components'=>array(
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
			'class'=>'WebUser',
			'loginUrl'=>array('user/login'),
		),
		// uncomment the following to enable URLs in path-format
		'urlManager'=>array(
			'urlFormat'=>'path',
			'showScriptName'=>false,
			'rules'=>array(
				'<type:tejia|gonglue>/<keyword:(.*)+>' => '/pin/search',
				'/Api/<controller:\w+>.<action:\w+>'=>'<controller>/<action>Ajax',
				'/signup' => '/user/signup',
				'/logout' => '/user/logout',
				'/gonglue' => '/pin/gonglue',
				'/tejia' => '/pin/tejia',
				'/top' => '/pin/top',
				'<controller:\w+>/<id:\d+>'=>'<controller>/detail',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),
		/*
		'db'=>array(
			'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
		),
		 */
		// uncomment the following to use a MySQL database
		'db'=>array(
			'connectionString' => 'mysql:host=127.0.0.1;dbname=trip007',
			'emulatePrepare' => true,
			'username' => 'trip007',
			'password' => '123456',
			'charset' => 'utf8',
		),
		'search' => array(
			'class' => 'ext.xunsearch.EXunSearch',
			'xsRoot' => '/Users/cgeek/xunsearch',  // xunsearch 安装目录
			'project' => 'pin', // 搜索项目名称或对应的 ini 文件路径
			'charset' => 'utf-8', // 您当前使用的字符集（索引、搜索结果）
		),
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning, info',
				),
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				 */
			),
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'cgeek.share@gmail.com',
	),

	'defaultController' => 'Pin',
);
