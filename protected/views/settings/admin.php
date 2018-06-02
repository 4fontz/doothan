<?php
/* @var $this SettingsController */
/* @var $model Settings */

$this->breadcrumbs=array(
	'Settings'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List Settings', 'url'=>array('index')),
	array('label'=>'Create Settings', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#settings-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Settings</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'settings-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'settings_id',
		'admin_email',
		'email_no_reply',
		'support_email',
		'facebook_app_id',
		'facebook_app_secret',
		/*
		'aws_key',
		'aws_secret',
		'aws_region',
		'profile_image_bucket',
		'profile_image_bucket_url',
		'adhar_image_bucket',
		'adhar_image_bucket_url',
		'photo_image_bucket',
		'photo_image_bucket_url',
		'request_image_bucket',
		'request_image_bucket_url',
		'upload_path',
		'from_mail',
		'from_name',
		'google_api_key',
		'nexmo_key',
		'nexmo_secret',
		'nexmo_sender_id',
		'_SALT',
		'minimum_km',
		'default_weight_limit',
		'default_distance_limit',
		'default_weight_limit_charge',
		'default_weight_charge',
		'default_distance_limit_charge',
		'default_distance_charge',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
