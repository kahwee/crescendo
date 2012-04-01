<?php

/**
 * CrescendoFileOwnerBehavior
 *
 * @author KahWee Teng <t@kw.sg>
 * @version 1.0
 * @link http://kw.sg/
 * @copyright Copyright &copy; 2011 KahWee Teng
 * @license http://www.opensource.org/licenses/mit-license.php
 */
class CrescendoFileOwnerBehavior extends CActiveRecordBehavior {

	/**
	 * Name of the model containing the CrescendoFileBehavior
	 * @var string
	 */
	public $crescendoFileModel = 'File';

	/**
	 * Named behavior inside $this->owner. Defaults to CrescendoFileBehavior.
	 * @var string
	 */
	public $crescendoFileBehavior = 'CrescendoFileBehavior';

	/**
	 * Name of the column that stores the foreign model name.
	 *
	 * @var string
	 */
	public $crescendoFileModelNameAttribute = 'model_name';

	/**
	 * Name of the column that stores the foreign model key.
	 *
	 * @var string
	 */
	public $crescendoFileModelIdAttribute = 'model_id';

	/**
	 * Name of file upload property that is in $this->owner.
	 *
	 * @var string
	 */
	public $crescendoFileOwnerFileProperty = 'file';
	private $_fileModel;
	private $_fileModelFileProperty;

	public function init() {
		$fileModel = $this->crescendoFileModel;
		$this->_fileModel = $fileModel::model();
		if (isset($this->_fileModel->{$this->crescendoFileBehavior})) {
			$this->_fileModelFileProperty = $this->_fileModel->{$this->crescendoFileBehavior}->crescendoFileFileProperty;
		} else {
			throw new CException("'$fileModel' needs to have behavior '$crescendoFileBehavior' to function.");
		}
	}

	/**
	 * Responds to {@link CActiveRecord::onAfterSave} event.
	 * Overrides this method if you want to handle the corresponding event of the {@link CBehavior::owner owner}.
	 * @param CModelEvent $event event parameter
	 */
	public function afterSave($event) {
		$this->init();
		$ownerClass = get_class($this->getOwner());
		$fileModel = new $this->_fileModel;
		$fileModel->{$this->crescendoFileModelNameAttribute} = $ownerClass;
		$fileModel->{$this->crescendoFileModelIdAttribute} = $this->getOwner()->primaryKey;
		$_FILES[$this->crescendoFileModel] = $_FILES[$ownerClass];
		foreach ($_FILES[$this->crescendoFileModel] as $k1 => &$v1) {
			foreach ($v1 as $k2 => $v2) {
				$v1[str_replace($this->crescendoFileOwnerFileProperty, $this->_fileModelFileProperty, $k2)] = $v2;
				unset($v1[$k2]);
			}
		}
		if (!$fileModel->save()) {
			$this->getOwner()->addErrors($fileModel->errors);
		}
	}

}

