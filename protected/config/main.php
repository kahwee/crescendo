<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
	'name' => 'My Web Application',
	// preloading 'log' component
	'preload' => array('log'),
	// autoloading model and component classes
	'import' => array(
		'application.models.*',
		'application.components.*',
	),
	'modules' => array(
		'gii' => array(
			'class' => 'system.gii.GiiModule',
			'password' => 'admin',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters' => array('127.0.0.1', '::1'),
		),
		'crescendo' => array(
			'class' => 'application.modules.crescendo.CrescendoModule',
		# 'uploadSourceDirectoryPath' => '/var/www/static/uploads', #Behaviors upload to this directory
		# 'uploadSourceUrlPath' => '/uploads',
		# 'imageCacheDirectoryPath' => '/var/www/static/uploads/cache', #CrescendoThumbnail caches thumbnails to this directory
		# 'imageCacheUrlPath' => '/uploads/cache',
		# 'imageNotAvailableUrlPath' => '/img/hello.png', #defaults to using Crescendo assets for no-image.png
		),
	),
	'components' => array(
		'user' => array(
			// enable cookie-based authentication
			'allowAutoLogin' => true,
		),
		'urlManager' => array(
			'urlFormat' => 'path',
			'rules' => array(
				'<controller:\w+>/<id:\d+>' => '<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
				'<controller:\w+>/<action:\w+>' => '<controller>/<action>',
			),
			'showScriptName' => false,
		),
		'db' => array(
			'connectionString' => 'sqlite:' . dirname(__FILE__) . '/../data/testdrive.db',
		),
		'errorHandler' => array(
			// use 'site/error' action to display errors
			'errorAction' => 'site/error',
		),
		'log' => array(
			'class' => 'CLogRouter',
			'routes' => array(
				array(
					'class' => 'CFileLogRoute',
					'levels' => 'error, warning',
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
	'params' => array(
		// this is used in contact page
		'adminEmail' => 'webmaster@example.com',
	),
);
