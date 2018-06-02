<?php
/* @var $this SettingsController */
/* @var $model Settings */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'settings_id'); ?>
		<?php echo $form->textField($model,'settings_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'admin_email'); ?>
		<?php echo $form->textField($model,'admin_email',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'email_no_reply'); ?>
		<?php echo $form->textField($model,'email_no_reply',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'support_email'); ?>
		<?php echo $form->textField($model,'support_email',array('size'=>60,'maxlength'=>150)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'facebook_app_id'); ?>
		<?php echo $form->textField($model,'facebook_app_id',array('size'=>60,'maxlength'=>250)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'facebook_app_secret'); ?>
		<?php echo $form->textField($model,'facebook_app_secret',array('size'=>60,'maxlength'=>250)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'aws_key'); ?>
		<?php echo $form->textField($model,'aws_key',array('size'=>60,'maxlength'=>250)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'aws_secret'); ?>
		<?php echo $form->textField($model,'aws_secret',array('size'=>60,'maxlength'=>250)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'aws_region'); ?>
		<?php echo $form->textField($model,'aws_region',array('size'=>60,'maxlength'=>150)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'profile_image_bucket'); ?>
		<?php echo $form->textField($model,'profile_image_bucket',array('size'=>60,'maxlength'=>250)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'profile_image_bucket_url'); ?>
		<?php echo $form->textField($model,'profile_image_bucket_url',array('size'=>60,'maxlength'=>250)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'adhar_image_bucket'); ?>
		<?php echo $form->textField($model,'adhar_image_bucket',array('size'=>60,'maxlength'=>250)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'adhar_image_bucket_url'); ?>
		<?php echo $form->textField($model,'adhar_image_bucket_url',array('size'=>60,'maxlength'=>250)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'photo_image_bucket'); ?>
		<?php echo $form->textField($model,'photo_image_bucket',array('size'=>60,'maxlength'=>250)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'photo_image_bucket_url'); ?>
		<?php echo $form->textField($model,'photo_image_bucket_url',array('size'=>60,'maxlength'=>250)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'request_image_bucket'); ?>
		<?php echo $form->textField($model,'request_image_bucket',array('size'=>60,'maxlength'=>250)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'request_image_bucket_url'); ?>
		<?php echo $form->textField($model,'request_image_bucket_url',array('size'=>60,'maxlength'=>250)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'upload_path'); ?>
		<?php echo $form->textField($model,'upload_path',array('size'=>60,'maxlength'=>250)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'from_mail'); ?>
		<?php echo $form->textField($model,'from_mail',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'from_name'); ?>
		<?php echo $form->textField($model,'from_name',array('size'=>60,'maxlength'=>150)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'google_api_key'); ?>
		<?php echo $form->textField($model,'google_api_key',array('size'=>60,'maxlength'=>150)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'nexmo_key'); ?>
		<?php echo $form->textField($model,'nexmo_key',array('size'=>60,'maxlength'=>150)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'nexmo_secret'); ?>
		<?php echo $form->textField($model,'nexmo_secret',array('size'=>60,'maxlength'=>150)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'nexmo_sender_id'); ?>
		<?php echo $form->textField($model,'nexmo_sender_id',array('size'=>60,'maxlength'=>150)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'_SALT'); ?>
		<?php echo $form->textField($model,'_SALT',array('size'=>60,'maxlength'=>150)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'minimum_km'); ?>
		<?php echo $form->textField($model,'minimum_km'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'default_weight_limit'); ?>
		<?php echo $form->textField($model,'default_weight_limit'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'default_distance_limit'); ?>
		<?php echo $form->textField($model,'default_distance_limit'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'default_weight_limit_charge'); ?>
		<?php echo $form->textField($model,'default_weight_limit_charge'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'default_weight_charge'); ?>
		<?php echo $form->textField($model,'default_weight_charge'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'default_distance_limit_charge'); ?>
		<?php echo $form->textField($model,'default_distance_limit_charge'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'default_distance_charge'); ?>
		<?php echo $form->textField($model,'default_distance_charge'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->