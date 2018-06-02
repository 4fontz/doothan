<style type="text/css">
.labl{
      display: inline-block;
    max-width: 100%;
    margin-bottom: 5px;
    font-weight: 700;
}
</style>
<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'feedback-form',
	'enableAjaxValidation'=>true,
	'htmlOptions'=>array('enctype'=>'multipart/form-data'),

)); ?>
<p class="help-block">fields with <span class="required">*</span> are required.</p>
  <?php echo $form->errorSummary($model); ?>
<section class="content">
  <div class="row">
    <div class="col-md-6">
      <div class="box box-primary">
        <div class="box-header with-border"></div>
          <div class="box-body">
            <div class="form-group">
              <div class="form-group">
                  <?php echo $form->labelEx($model,'user_id'); 
                  $models = Users::model()->findAll(array('order' => 'first_name'));?>
                  <?php echo CHtml::activeDropDownList($model, 'user_id', CHtml::ListData($models, 'id', 'first_name'),array('empty' => 'Select a user')) ?>
                  <?php echo $form->error($model,'user_id'); ?>
              </div>  
              <div class="form-group">
                  <?php echo $form->labelEx($model,'feedback'); ?>
                  <?php echo $form->textArea($model,'feedback',array('class'=>'form-control')); ?>
                  <?php echo $form->error($model,'feedback'); ?>
              </div>  
              <div class="form-group">
					<?php $this->widget('bootstrap.widgets.TbButton', array(
						'buttonType'=>'submit',
						'type'=>'primary',
						'label'=>$model->isNewRecord ? 'create' : 'save',
					)); ?>
              </div>                
            </div>
          </div>
      </div>
    </div>
  </div>
</section>
<?php $this->endWidget(); ?>



