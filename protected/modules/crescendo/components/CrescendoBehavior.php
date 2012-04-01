<?php

/**
 * CrescendoBehavior
 *
 * @author KahWee Teng <t@kw.sg>
 * @version 1.0
 * @link http://kw.sg/
 * @copyright Copyright &copy; 2011 KahWee Teng
 * @license http://www.opensource.org/licenses/mit-license.php
 */
class CrescendoBehavior extends CActiveRecordBehavior {

	public $fileProperty = 'file';
	public $nameField = 'name';
	public $mimeTypeField = 'mime_type';

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

	/**
	 * Responds to {@link CModel::onAfterConstruct} event.
	 * Overrides this method if you want to handle the corresponding event of the {@link CBehavior::owner owner}.
	 * @param CEvent $event event parameter
	 */
	public function __construct() {
		if (is_null($this->uploadPath)) {
			$this->_uploadPath = Yii::app()->getModule('crescendo')->uploadSourceDirectoryPath;
		} else {
			$this->_uploadPath = $this->uploadPath;
		}
	}

	public function getFilePath($fileName) {
		return $this->_uploadPath . DIRECTORY_SEPARATOR . $fileName;
	}

	public function afterDelete() {
		@unlink($this->getFilePath($this->getOwner()->{$this->nameField}));
	}

	public function afterSave($event) {
		$this->getOwner()->{$this->fileProperty}->saveAs($this->getFilePath($this->_fileName));
	}

	public function beforeValidate($event) {
		$file = $this->getOwner()->{$this->fileProperty} = CUploadedFile::getInstance($this->getOwner(), $this->fileProperty);
		if ($file instanceof CUploadedFile) {
			if (!empty($file->tempName)) {
				if (!empty($this->mimeTypeField)) {
					$this->getOwner()->{$this->mimeTypeField} = @CFileHelper::getMimeType($file->tempName);
				}
				$this->_extension = @CFileHelper::getExtension($file->name);
			}
			$this->_fileName = $this->randomHash() . (empty($this->_extension) ? '' : ".{$this->_extension}");
			$this->getOwner()->{$this->nameField} = $this->_fileName;
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

