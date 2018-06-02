<?php
/* @var $this SettingsController */
/* @var $model Settings */

$this->breadcrumbs=array(
	'Settings'=>array('index'),
	$model->settings_id,
);

$this->menu=array(
	array('label'=>'List Settings', 'url'=>array('index')),
	array('label'=>'Create Settings', 'url'=>array('create')),
	array('label'=>'Update Settings', 'url'=>array('update', 'id'=>$model->settings_id)),
	array('label'=>'Delete Settings', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->settings_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Settings', 'url'=>array('admin')),
);
?>

<h1>View Settings #<?php echo $model->settings_id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'settings_id',
		'admin_email',
		'email_no_reply',
		'support_email',
		'facebook_app_id',
		'facebook_app_secret',
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
	),
)); ?>
