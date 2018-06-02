<?php
/* @var $this SettingsController */
/* @var $data Settings */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('settings_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->settings_id), array('view', 'id'=>$data->settings_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('admin_email')); ?>:</b>
	<?php echo CHtml::encode($data->admin_email); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('email_no_reply')); ?>:</b>
	<?php echo CHtml::encode($data->email_no_reply); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('support_email')); ?>:</b>
	<?php echo CHtml::encode($data->support_email); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('facebook_app_id')); ?>:</b>
	<?php echo CHtml::encode($data->facebook_app_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('facebook_app_secret')); ?>:</b>
	<?php echo CHtml::encode($data->facebook_app_secret); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('aws_key')); ?>:</b>
	<?php echo CHtml::encode($data->aws_key); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('aws_secret')); ?>:</b>
	<?php echo CHtml::encode($data->aws_secret); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('aws_region')); ?>:</b>
	<?php echo CHtml::encode($data->aws_region); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('profile_image_bucket')); ?>:</b>
	<?php echo CHtml::encode($data->profile_image_bucket); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('profile_image_bucket_url')); ?>:</b>
	<?php echo CHtml::encode($data->profile_image_bucket_url); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('adhar_image_bucket')); ?>:</b>
	<?php echo CHtml::encode($data->adhar_image_bucket); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('adhar_image_bucket_url')); ?>:</b>
	<?php echo CHtml::encode($data->adhar_image_bucket_url); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('photo_image_bucket')); ?>:</b>
	<?php echo CHtml::encode($data->photo_image_bucket); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('photo_image_bucket_url')); ?>:</b>
	<?php echo CHtml::encode($data->photo_image_bucket_url); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('request_image_bucket')); ?>:</b>
	<?php echo CHtml::encode($data->request_image_bucket); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('request_image_bucket_url')); ?>:</b>
	<?php echo CHtml::encode($data->request_image_bucket_url); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('upload_path')); ?>:</b>
	<?php echo CHtml::encode($data->upload_path); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('from_mail')); ?>:</b>
	<?php echo CHtml::encode($data->from_mail); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('from_name')); ?>:</b>
	<?php echo CHtml::encode($data->from_name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('google_api_key')); ?>:</b>
	<?php echo CHtml::encode($data->google_api_key); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('nexmo_key')); ?>:</b>
	<?php echo CHtml::encode($data->nexmo_key); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('nexmo_secret')); ?>:</b>
	<?php echo CHtml::encode($data->nexmo_secret); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('nexmo_sender_id')); ?>:</b>
	<?php echo CHtml::encode($data->nexmo_sender_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('_SALT')); ?>:</b>
	<?php echo CHtml::encode($data->_SALT); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('minimum_km')); ?>:</b>
	<?php echo CHtml::encode($data->minimum_km); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('default_weight_limit')); ?>:</b>
	<?php echo CHtml::encode($data->default_weight_limit); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('default_distance_limit')); ?>:</b>
	<?php echo CHtml::encode($data->default_distance_limit); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('default_weight_limit_charge')); ?>:</b>
	<?php echo CHtml::encode($data->default_weight_limit_charge); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('default_weight_charge')); ?>:</b>
	<?php echo CHtml::encode($data->default_weight_charge); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('default_distance_limit_charge')); ?>:</b>
	<?php echo CHtml::encode($data->default_distance_limit_charge); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('default_distance_charge')); ?>:</b>
	<?php echo CHtml::encode($data->default_distance_charge); ?>
	<br />

	*/ ?>

</div>