<?php
Yii::import('system.web.widgets');
/**
 * CrescendoHelper
 *
 * @author KahWee Teng <t@kw.sg>
 * @version 1.3
 * @link http://kw.sg/
 * @copyright Copyright &copy; 2011 KahWee Teng
 * @license http://www.opensource.org/licenses/mit-license.php
 */
class CrescendoImage extends CWidget {
	public $name;
	public $width;
	public $height;
	/**
	 * @var array additional HTML attributes that will be rendered in the image tag.
	 */
	public $options;

	/**
	 * Initializes the widget.
	 * This method registers all needed client scripts and renders
	 * the tree view content.
	 */
	public function init() {
		require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'CrescendoThumbnail.php');
		echo CrescendoThumbnail::image($this->name, $this->width, $this->height, $this->options);
	}
}

