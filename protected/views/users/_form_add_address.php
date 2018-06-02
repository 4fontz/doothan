

<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
  'id'=>'users-form',
  'enableAjaxValidation'=>false,
  'htmlOptions'=>array('enctype'=>'multipart/form-data'),

)); ?>



    <p class="help-block">fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary($model); ?>

    <section class="content">
      <div class="row">
        <!-- left column -->
        
        <div class="col-md-6">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <!-- <h3 class="box-title">Quick Example</h3> -->
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            
              <div class="box-body">
                <div class="form-group">
              <div class="form-group">
                <?php //print_r($userAddressModel); ?>
                <?php echo $form->labelEx($model,'block'); ?>
                <?php echo $form->textField($model,'block',array('class'=>'form-control class-small','maxlength'=>200)); ?>
                <?php echo $form->hiddenField($model,'user_id',array('class'=>'form-control class-small','maxlength'=>200)); ?>
                <?php echo $form->error($model,'block'); ?>
              </div>

              <div class="form-group">
                <?php echo $form->labelEx($model,'room'); ?>
                <?php echo $form->textField($model,'room',array('class'=>'form-control class-small','maxlength'=>200)); ?>
                <?php echo $form->error($model,'room'); ?>
              </div>

              

              <div class="form-group">
                <?php echo $form->labelEx($model,'street_name'); ?>
                <?php echo $form->textField($model,'street_name',array('class'=>'form-control class-small','maxlength'=>200)); ?>
                <?php echo $form->error($model,'street_name'); ?>
              </div>

              <div class="form-group">
                <?php echo $form->labelEx($model,'property_name'); ?>
                <?php echo $form->textField($model,'property_name',array('class'=>'form-control class-small','maxlength'=>200)); ?>
                <?php echo $form->error($model,'property_name'); ?>
              </div>

              <div class="form-group">
                <?php echo $form->labelEx($model,'default address'); ?> &nbsp;&nbsp;&nbsp;
                <?php echo CHtml::activeCheckBox($model,'is_default',array()); ?>
                <?php echo $form->error($model,'property_name'); ?>
              </div>
                  
                  
                </div>
              </div>
            
          </div>
        </div>

        <div class="col-md-6">
            <div class="box box-danger">
                <div class="box-header with-border">
                </div>
                
                    <div class="box-body">
                        <div class="form-group">
              <div class="form-group">
                <?php echo $form->labelEx($model,'city'); ?>
                <?php echo $form->textField($model,'city',array('class'=>'form-control class-small','maxlength'=>200)); ?>
                <?php echo $form->error($model,'city'); ?>
              </div>
              <div class="form-group">
                <?php echo $form->labelEx($model,'address_label'); ?>
                <?php echo $form->textField($model,'address_label',array('class'=>'form-control class-small','maxlength'=>200)); ?>
                <?php echo $form->error($model,'address_label'); ?>
              </div>

              <div class="form-group">
                <?php echo $form->labelEx($model,'postal_code'); ?>
                <?php echo $form->textField($model,'postal_code',array('class'=>'form-control class-small','maxlength'=>100)); ?>
                <?php //echo CHtml::activeDropDownList($userAddressModel, 'postal_code', Common::$statecapzip_list,array('style'=>'width:150px;')) ?>
                <?php //echo $form->textField($model,'postal_code',array('class'=>'form-control','maxlength'=>100)); ?>
                <?php echo $form->error($model,'postal_code'); ?>
              </div>

              <div class="box-footer">
                <div class="form-actions">
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

      </div>
    </section>

    

<?php $this->endWidget(); ?>



    

    





