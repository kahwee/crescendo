<?php

/**
 * CrescendoFileBehavior
 *
 * @author KahWee Teng <t@kw.sg>
 * @version 1.0
 * @link http://kw.sg/
 * @copyright Copyright &copy; 2011 KahWee Teng
 * @license http://www.opensource.org/licenses/mit-license.php
 */
class CrescendoFileBehavior extends CActiveRecordBehavior {

	/**
	 * Name of file upload property that is in $this->owner.
	 * This is for the File model.
	 *
	 * @var string
	 */
	public $crescendoFileFileProperty = 'file';
	public $crescendoFileNameAttribute = 'name';
	public $mimeTypeAttribute = 'mime_type';

	/**
	 * Yii path for uploads, using getPathOfAlias
	 * @var string
	 */
	public $uploadPath = null;

	/**
	 * Extension of the file.
	 * @var string
	 */
	private $_extension = '';

	/**
	 * Generated filename, including the extension.
	 * @var string
	 */
	private $_fileName = '';
	private $_uploadPath = '';

	public function init() {
		if (is_null($this->uploadPath)) {
			$this->_uploadPath = Yii::app()->getModule('crescendo')->uploadSourceDirectoryPath;
		} else {
			$this->_uploadPath = $this->uploadPath;
		}
	}

	public function getUploadSourceDirectoryPath($fileName) {
		$parts = explode(DIRECTORY_SEPARATOR, $fileName);
		$uploadSourceDirectory = $this->_uploadPath;
		if (!is_dir($uploadSourceDirectory)) {
			if (!@mkdir($uploadSourceDirectory, 0777)) {
				throw new CException("Cannot create directory: '$uploadSourceDirectory'. Check if you have assigned write permissions parent directory.");
			}
		}
		for ($i = 0; $i < count($parts) - 1; $i++) {
			$uploadSourceDirectory .= DIRECTORY_SEPARATOR . $parts[$i];
			if (!is_dir($uploadSourceDirectory)) {
				if (!@mkdir($uploadSourceDirectory, 0777)) {
					throw new CException("Cannot create directory: '$uploadSourceDirectory'. Check if you have assigned write permissions parent directory.");
				}
			}
		}
		return $uploadSourceDirectory . DIRECTORY_SEPARATOR . $parts[count($parts) - 1];
	}

	/**
	 * Responds to {@link CActiveRecord::onAfterDelete} event.
	 * Overrides this method if you want to handle the corresponding event of the {@link CBehavior::owner owner}.
	 * @param CEvent $event event parameter
	 */
	public function afterDelete($event) {
		$this->init();
		@unlink($this->getUploadSourceDirectoryPath($this->getOwner()->{$this->crescendoFileNameAttribute}));
	}

	/**
	 * Responds to {@link CActiveRecord::onAfterSave} event.
	 * Overrides this method if you want to handle the corresponding event of the {@link CBehavior::owner owner}.
	 * @param CModelEvent $event event parameter
	 */
	public function afterSave($event) {
		$this->init();
		$owner = $this->getOwner()->findByPk($this->getOwner()->id);
		$this->getOwner()->{$this->crescendoFileFileProperty}->saveAs($this->getUploadSourceDirectoryPath($this->_fileName));
	}

	public function beforeValidate($event) {
		$this->init();
		$file = $this->getOwner()->{$this->crescendoFileFileProperty} = CUploadedFile::getInstance($this->getOwner(), $this->crescendoFileFileProperty);
		if ($file instanceof CUploadedFile) {
			if (!empty($file->tempName)) {
				if (!empty($this->mimeTypeAttribute)) {
					$this->getOwner()->{$this->mimeTypeAttribute} = @CFileHelper::getMimeType($file->tempName);
				}
				$this->_extension = @CFileHelper::getExtension($file->name);
			}
			$createdAtDateTime = new DateTime('now');
			$this->_fileName = $createdAtDateTime->format('Y-m') . DIRECTORY_SEPARATOR . $this->randomHash() . (empty($this->_extension) ? '' : ".{$this->_extension}");
			$this->getOwner()->{$this->crescendoFileNameAttribute} = $this->_fileName;
		}
	}

	/**
	 * Generate a somewhat unique and randomized hash for the filename.
	 *
	 * @return string Unique and random hash for filename generation.
	 */
	public function randomHash() {
		return substr(md5(uniqid()), 3, 12) . uniqid();
	}

}

