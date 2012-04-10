<?php

class CrescendoModule extends CWebModule {

	public $uploadSourceDirectoryPath;
	public $uploadSourceUrlPath;
	public $imageCacheDirectoryPath;
	public $imageCacheUrlPath;
	public $imageNotAvailableUrlPath;

	public function init() {
		if (empty($this->uploadSourceDirectoryPath)) {
			$this->uploadSourceDirectoryPath = Yii::getPathOfAlias('webroot.uploads');
		}
		if (empty($this->uploadSourceUrlPath)) {
			$this->uploadSourceUrlPath = '/uploads';
		}
		if (empty($this->imageCacheDirectoryPath)) {
			$this->imageCacheDirectoryPath = Yii::getPathOfAlias('webroot.uploads.cache');
		}
		if (empty($this->imageCacheUrlPath)) {
			$this->imageCacheUrlPath = '/uploads/cache';
		}
		if (empty($this->imageNotAvailableUrlPath)) {
			$asset_url = Yii::app()->assetManager->publish(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'assets', false, -1, true);
			$this->imageNotAvailableUrlPath = $asset_url . '/no-image.png';
		}
		$this->setImport(array(
			'crescendo.models.*',
			'crescendo.components.*',
		));
	}

	public function beforeControllerAction($controller, $action) {
		if (parent::beforeControllerAction($controller, $action)) {
			// this method is called before any module controller action is performed
			// you may place customized code here
			return true;
		}
		else
			return false;
	}

}
