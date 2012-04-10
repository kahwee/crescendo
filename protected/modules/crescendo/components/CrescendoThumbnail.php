<?php
/**
 * CrescendoHelper
 *
 * @author KahWee Teng <t@kw.sg>
 * @version 1.3
 * @link http://kw.sg/
 * @copyright Copyright &copy; 2011 KahWee Teng
 * @license http://www.opensource.org/licenses/mit-license.php
 */
class CrescendoThumbnail extends KThumbnail {

	public static function getImageCacheDirectoryPath() {
		$imageCacheDirectoryPath = Yii::app()->getModule('crescendo')->imageCacheDirectoryPath;
		if (!is_dir($imageCacheDirectoryPath)) {
			mkdir($imageCacheDirectoryPath, 0777);
		}
		return $imageCacheDirectoryPath;
	}

	public static function getImageCacheUrlPath() {
		return Yii::app()->getModule('crescendo')->imageCacheUrlPath;
	}

	public static function getImageSourceDirectoryPath() {
		$imageSourceDirectoryPath = Yii::app()->getModule('crescendo')->uploadSourceDirectoryPath;
		if (!is_dir($imageSourceDirectoryPath)) {
			mkdir($imageSourceDirectoryPath, 0777);
		}
		return Yii::app()->getModule('crescendo')->uploadSourceDirectoryPath;
	}

	public static function getImageSourceUrlPath() {
		return Yii::app()->getModule('crescendo')->uploadSourceUrlPath;
	}

}

/**
 * KThumbnail is a static class that provides image resizing facility through phpthumb.
 *
 * Static binds late.
 *
 * @author KahWee Teng <t@kw.sg>
 * @version 1.3
 * @link http://kw.sg/
 * @copyright Copyright &copy; 2011 KahWee Teng
 * @license http://www.opensource.org/licenses/mit-license.php
 */
class KThumbnail {

	public static function getImageNotAvailableUrlPath() {
		return 'http://upload.wikimedia.org/wikipedia/en/d/d1/Image_not_available.png';
	}

	public static function getImageCacheDirectoryPath() {
		return Yii::getPathOfAlias('webroot.uploads');
	}

	public static function getImageCacheUrlPath() {
		return '/uploads';
	}

	public static function getImageSourceDirectoryPath() {
		return Yii::getPathOfAlias('webroot.uploads');
	}

	public static function getImageSourceUrlPath() {
		return '/uploads';
	}

	/**
	 *
	 * @return string The parth of phpthumb class
	 */
	public static function getPhpThumbPath() {
		return dirname(__dir__) . DIRECTORY_SEPARATOR . 'vendors' . DIRECTORY_SEPARATOR . 'phpthumb' . DIRECTORY_SEPARATOR . 'ThumbLib.inc.php';
	}

	/**
	 * Generates an image tag, image is resized and cached
	 *
	 * @param string $src image filename or the image URL
	 * @param integer $width Width of image
	 * @param integer $height Height of image
	 * @param string $alt the alternative text display
	 * @param array $options Remaining options are passed to $htmlOptions for CHtml::image
	 * @return string the generated image tag
	 */
	public static function image($src, $width=null, $height=null, $alt='', $options=array()) {
		#normalizing nulls and zeroes
		$height = (int) $height;
		$width = (int) $width;
		if (!empty($height)) {
			$options['height'] = $height;
		}
		if (!empty($width)) {
			$options['width'] = $width;
		}
		#if it is url, ignore.
		if (strpos($src, 'http://') === 0 || strpos($src, 'https://') === 0) {
			return CHtml::image($src, $alt, $options);
		}
		#get image from source
		if (isset($options['imageSourceDirectoryPath'])) {
			$imageSourceDirectoryPath = $options['imageSourceDirectoryPath'];
		} else {
			$imageSourceDirectoryPath = static::getImageSourceDirectoryPath();
		}
		$imageSourceFilePath = $imageSourceDirectoryPath . DIRECTORY_SEPARATOR . $src;
		if (empty($imageSourceFilePath) || !file_exists($imageSourceFilePath)) {
			return CHtml::image(static::getImageNotAvailableUrlPath(), $alt, $options);
		}
		#caching the resize appropriately
		if (isset($options['imageCacheDirectoryPath'])) {
			$imageCacheDirectoryPath = $options['imageCacheDirectoryPath'];
		} else {
			$imageCacheDirectoryPath = static::getImageCacheDirectoryPath();
		}
		#create directory if not exist
		$imageCacheDirectoryPath .= DIRECTORY_SEPARATOR . $width . 'x' . $height;
		if (!is_dir($imageCacheDirectoryPath)) {
			mkdir($imageCacheDirectoryPath, 0777);
		}
		$parts = explode(DIRECTORY_SEPARATOR, $src);
		for ($i = 0; $i < count($parts) - 1; $i++) {
			$imageCacheDirectoryPath .= DIRECTORY_SEPARATOR . $parts[$i];
			if (!is_dir($imageCacheDirectoryPath)) {
				mkdir($imageCacheDirectoryPath, 0777);
			}
		}
		$imageCacheFilePath =  $imageCacheDirectoryPath . DIRECTORY_SEPARATOR . $parts[count($parts) - 1];
		if (!file_exists($imageCacheFilePath)) {
			include_once(static::getPhpThumbPath());
			#caching the resize appropriately
			$thumb = PhpThumbFactory::create($imageSourceFilePath);
			#the various resize
			if (!empty($height) && !empty($width)) {
				$thumb->adaptiveResize($width, $height);
			} else {
				$thumb->resize($width, 0);
			}
			$thumb->save($imageCacheFilePath);
		}
		$imageCacheUrl = static::getImageCacheUrlPath() . '/' . $width . 'x' . $height . '/' . $src;
		return CHtml::image($imageCacheUrl, $alt, $options);
	}

}
