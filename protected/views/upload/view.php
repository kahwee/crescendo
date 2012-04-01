<?php
$this->breadcrumbs=array(
	'Uploads'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Upload', 'url'=>array('index')),
	array('label'=>'Create Upload', 'url'=>array('create')),
	array('label'=>'Update Upload', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Upload', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Upload', 'url'=>array('admin')),
);
?>

<h1>View Upload #<?php echo $model->id; ?></h1>

<?php
Yii::import('application.modules.crescendo.components.CrescendoHelper');

echo CrescendoHelper::image($model->name, 100, 270);
echo CrescendoHelper::image('http://l.yimg.com/a/i/ww/met/unsupprtd_brwsr/yahoo_logo_sg_083109.gif', 100);

$this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
	),
));

?>
