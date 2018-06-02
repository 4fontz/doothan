<?php
/* @var $this NotificationsController */
/* @var $model Notifications */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'notifications-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'request_id'); ?>
		<?php echo $form->textField($model,'request_id'); ?>
		<?php echo $form->error($model,'request_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'notification'); ?>
		<?php echo $form->textArea($model,'notification',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'notification'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'created_at'); ?>
		<?php echo $form->textField($model,'created_at'); ?>
		<?php echo $form->error($model,'created_at'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->