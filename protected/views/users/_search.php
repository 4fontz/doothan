<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<?php echo $form->textFieldRow($model,'id',array('class'=>'form-control')); ?>

	<?php echo $form->textFieldRow($model,'username',array('class'=>'form-control','maxlength'=>30)); ?>

	<?php echo $form->textFieldRow($model,'first_name',array('class'=>'form-control','maxlength'=>100)); ?>

	<?php echo $form->textFieldRow($model,'last_name',array('class'=>'form-control','maxlength'=>100)); ?>

	<?php echo $form->textFieldRow($model,'email',array('class'=>'form-control','maxlength'=>200)); ?>

	<?php echo $form->textFieldRow($model,'phone',array('class'=>'form-control','maxlength'=>10)); ?>

	<?php echo $form->textFieldRow($model,'city',array('class'=>'form-control','maxlength'=>100)); ?>

	<?php echo $form->textFieldRow($model,'state',array('class'=>'form-control','maxlength'=>100)); ?>

	<?php echo $form->textFieldRow($model,'country',array('class'=>'form-control','maxlength'=>100)); ?>

	<?php echo $form->textFieldRow($model,'zip',array('class'=>'form-control','maxlength'=>20)); ?>

	<?php echo $form->textFieldRow($model,'image',array('class'=>'form-control','maxlength'=>150)); ?>

	<?php echo $form->textFieldRow($model,'facebook_image',array('class'=>'form-control','maxlength'=>300)); ?>

	<?php echo $form->textFieldRow($model,'status',array('class'=>'form-control')); ?>

	<?php echo $form->textFieldRow($model,'verification_code',array('class'=>'form-control','maxlength'=>100)); ?>

	<?php echo $form->textFieldRow($model,'invite_code',array('class'=>'form-control','maxlength'=>50)); ?>

	<?php echo $form->textFieldRow($model,'invited_by',array('class'=>'form-control')); ?>

	<?php echo $form->textFieldRow($model,'member_type',array('class'=>'form-control')); ?>

	<?php echo $form->textFieldRow($model,'created',array('class'=>'form-control')); ?>

	<?php echo $form->textFieldRow($model,'updated',array('class'=>'form-control')); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>'Search',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
