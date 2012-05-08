Highly extensible uploader for Yii, serves with icing 𝆒
========================================

What is Crescendo?
------------------

Crescendo is a set of tools to let developers implement uploading. It includes the following:

 * CrescendoFileBehavior: A behavior that handles uploads.
 * CrescendoFileOwnerBehavior: A behavior that handles relations of uploads.
 * CrescendoHelper: A helper that returns the HTML image element after doing resizing on the fly using the wonderous phpthumb class.

How to make it work
-------------------

Deploy it by placing it Crescendo into `protected/modules/crescendo`.

And in your `./protected/config/main.php`, add `crescendo` to begin:

```php
<?php
return array(
	'modules' => array(
		'crescendo' => array(),
	),
);
```

More advance usage
------------------

For more advance usage, here is an example.

```php
<?php
return array(
	'modules' => array(
		'crescendo' => array(
			'class' => 'application.modules.crescendo.CrescendoModule',
			'uploadSourceDirectoryPath' => '/var/www/static/uploads', #Behaviors upload to this directory
			'uploadSourceUrlPath' => '/uploads',
			'imageCacheDirectoryPath' => '/var/www/static/uploads', #CrescendoHelper caches thumbnails to this directory
			'imageCacheUrlPath' => '/uploads',
		),
	),
);
```

Issues?
-------

If you have any issues, please highlight them in [Crescendo's GitHub issues](https://github.com/kahwee/crescendo/issues).
