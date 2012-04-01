<?php

return CMap::mergeArray(
		require('main.php'), array(
		'components' => array(
			'db' => array(
				'connectionString' => 'mysql:host=127.0.0.1;dbname=crescendo',
				'emulatePrepare' => true,
				'username' => 'root',
				'password' => 'root',
				'charset' => 'utf8',
			),
			'log' => array(
				'class' => 'CLogRouter',
				'routes' => array(
					array(
						'class' => 'CFileLogRoute',
						'levels' => 'error, warning',
					),
					array(
						'class' => 'CWebLogRoute',
						'categories' => 'system.db.CDbCommand',
						'showInFireBug' => true,
					),
				),
			),
		)
		)
);
?>
