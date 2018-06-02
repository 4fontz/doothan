<style type="text/css">
.labl{
      display: inline-block;
    max-width: 100%;
    margin-bottom: 5px;
    font-weight: 700;
}
</style>
<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'users-form',
	'enableAjaxValidation'=>false,
	'htmlOptions'=>array('enctype'=>'multipart/form-data'),
  'enableClientValidation' => false,
)); ?>

<?php 

    //die();
?>

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
                      <?php echo $form->labelEx($model,'first_name'); ?>
                      <?php echo $form->textField($model,'first_name',array('class'=>'form-control class-small','maxlength'=>100)); ?>
                      <?php echo $form->error($model,'first_name'); ?>
                  </div>

                  <div class="form-group">
                      <?php echo $form->labelEx($model,'email'); ?>
                      <?php echo $form->textField($model,'email',array('class'=>'form-control class-small','maxlength'=>100)); ?>
                      <?php echo $form->error($model,'email'); ?>
                  </div>
                  
                  <?php if ($model->isNewRecord) { ?> 
                  <div class="form-group">
                      <?php echo $form->labelEx($model,'password'); ?>
                      <?php echo $form->passwordField($model,'password',array('class'=>'form-control','maxlength'=>60)); ?>
                      <?php echo $form->error($model,'password'); ?>
                  </div>
                  <?php } else { ?>
                  <div class="form-group">
                      <?php echo $form->labelEx($model,'password'); ?>
                      <?php echo $form->passwordField($model,'password',array('class'=>'form-control','maxlength'=>60,'disabled'=>'disabled')); ?>
                      <?php echo $form->error($model,'password'); ?>
                  </div>    
                  <?php } ?>
                  
                  <div class="form-group">
                      <?php // echo $form->labelEx($model,'last_name'); ?>
                      <?php // echo $form->textField($model,'last_name',array('class'=>'form-control class-small','maxlength'=>100)); ?>
                      <?php // echo $form->error($model,'last_name'); ?>
                  </div>


                  <div class="form-group">
                              <div class="row">
                                <div class="col-md-4">
                                  <?php echo $form->labelEx($model,'country_code'); ?>
                                  <?php echo $form->textField($model,'country_code',array('class'=>'form-control class-small','maxlength'=>10)); ?>
                                    <?php echo $form->error($model,'country_code'); ?>
                                  </div>
                                  <div class="col-md-8">
                                    <?php echo $form->labelEx($model,'phone'); ?>
                                    <?php echo $form->textField($model,'phone',array('class'=>'form-control','maxlength'=>10,'minlength'=>7)); ?>
                                    <?php echo $form->error($model,'phone'); ?>
                                  </div>
                              </div>
                            </div>

                            <div class="form-group">
                              <div class="row">
                                      <div class="col-md-4">
                                        <!-- <p class="labl">image : </p> --> 
                                        
                                        <?php if ($model->image != '') { ?>
                                              <!-- <img width="65px" height="65px" style="border-radius:40%;" src="<?php echo Yii::app()->params['profileImageBucketUrl'].$model['image']; ?>"> -->
                                        <?php   }elseif ($model->facebook_image != '') { ?>
                                              <!-- <img width="65px" height="65px" style="border-radius:40%;" src="<?php echo $model['facebook_image']; ?>"> -->
                                        <?php  }  else {  ?>
                                              <!-- <p>choose an image </p> -->
                                        <?php   }  ?>
                                      </div>  
                                
                                      <div class="col-md-8">
                                     
                                          <?php //echo $form->label($model, 'image')?>
                                          <?php //echo $form->fileField($model, 'image')?>
                                          <?php //echo $form->error($model, 'image')?>
                                      </div>
                              </div>
                           </div>

                           <div class="form-group">
                            
                            <?php echo $form->labelEx($addressModel,'block'); ?>
                            <?php echo $form->textField($addressModel,'block',array('class'=>'form-control class-small','maxlength'=>200)); ?>
                            <?php echo $form->hiddenField($addressModel,'user_id',array('class'=>'form-control class-small','maxlength'=>200)); ?>
                            <?php echo $form->error($addressModel,'block'); ?>
                          </div>
                  
                  <!-- 
                  <div class="form-group">

                      <?php echo $form->labelEx($model,'invite_code'); ?>
                      <?php echo $form->textField($model,'invite_code',array('class'=>'form-control class-small','maxlength'=>100)); ?>
                      <?php echo $form->error($model,'invite_code'); ?>
                  </div>
                  <?php if ($model->isNewRecord) { ?> 
                  <div class="form-group">
                      <?php echo $form->labelEx($model,'invited_by'); ?>
                      <?php echo $form->textField($model,'invited_by',array('class'=>'form-control class-small','maxlength'=>100)); ?>
                      <?php echo $form->error($model,'invited_by'); ?>
                  </div>
                  <?php } else { ?>
                    <?php ($model['invited_by'] == 0) ? $model['invited_by'] = 'none' : $model['invited_by'] = $model['invited_by'];   ?>
                      <div class="form-group">
                      <?php echo $form->labelEx($model,'invited_by'); ?>
                      <?php echo $form->textField($model,'invited_by',array('class'=>'form-control class-small','maxlength'=>100,'disabled'=>'disabled')); ?>
                      <?php echo $form->error($model,'invited_by'); ?>
                  </div>
                  <?php  }    ?>


                   -->

                  

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
                            <?php echo $form->labelEx($addressModel,'room'); ?>
                            <?php echo $form->textField($addressModel,'room',array('class'=>'form-control class-small','maxlength'=>200)); ?>
                            <?php echo $form->error($addressModel,'room'); ?>
                          </div>

                          

                          <div class="form-group">
                            <?php echo $form->labelEx($addressModel,'street_name'); ?>
                            <?php echo $form->textField($addressModel,'street_name',array('class'=>'form-control class-small','maxlength'=>200)); ?>
                            <?php echo $form->error($addressModel,'street_name'); ?>
                          </div>


                          <div class="form-group">
                            <?php echo $form->labelEx($addressModel,'property_name'); ?>
                            <?php echo $form->textField($addressModel,'property_name',array('class'=>'form-control class-small','maxlength'=>200)); ?>
                            <?php echo $form->error($addressModel,'property_name'); ?>
                          </div>
<!-- 
                          <div class="form-group">
                            <?php echo $form->labelEx($addressModel,'default address'); ?> &nbsp;&nbsp;&nbsp;
                            <?php echo CHtml::activeCheckBox($addressModel,'is_default',array()); ?>
                            <?php echo $form->error($addressModel,'property_name'); ?>
                          </div> -->

                          <div class="form-group">
                            <?php echo $form->labelEx($addressModel,'city'); ?>
                            <?php echo $form->textField($addressModel,'city',array('class'=>'form-control class-small','maxlength'=>200)); ?>
                            <?php echo $form->error($addressModel,'city'); ?>
                          </div>
                          <div class="form-group">
                            <?php echo $form->labelEx($addressModel,'address_label'); ?>
                            <?php echo $form->textField($addressModel,'address_label',array('class'=>'form-control class-small','maxlength'=>200)); ?>
                            <?php echo $form->error($addressModel,'address_label'); ?>
                          </div>

                          <div class="form-group">
                            <?php echo $form->labelEx($addressModel,'postal_code'); ?>
                            <?php echo $form->textField($addressModel,'postal_code',array('class'=>'form-control class-small','maxlength'=>100)); ?>
                            <?php //echo CHtml::activeDropDownList($userAddressModel, 'postal_code', Common::$statecapzip_list,array('style'=>'width:150px;')) ?>
                            <?php //echo $form->textField($model,'postal_code',array('class'=>'form-control','maxlength'=>100)); ?>
                            <?php echo $form->error($addressModel,'postal_code'); ?>
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



