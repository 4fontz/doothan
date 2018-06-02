
<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'users-form',
	'enableAjaxValidation'=>true,
	'htmlOptions'=>array('enctype'=>'multipart/form-data'),

)); ?>
    <p class="help-block">fields with <span class="required">*</span> are required.</p>
      <?php echo $form->errorSummary($model); ?>
      <?php echo $form->errorSummary($addressEdtModel); ?>
      <div class="row">
        <div class="col-md-6">
          <div class="box box-primary">
            <div class="box-header with-border"></div>
              <div class="box-body">
                <div class="form-group">
                  <div class="form-group">
                      <?php echo $form->labelEx($model,'first_name'); ?>
                      <?php echo $form->textField($model,'first_name',array('class'=>'form-control','maxlength'=>100)); ?>
                      <?php echo $form->error($model,'first_name'); ?>
                  </div>
                  <div class="form-group">
                      <?php echo $form->labelEx($model,'last_name'); ?>
                      <?php echo $form->textField($model,'last_name',array('class'=>'form-control','maxlength'=>100)); ?>
                      <?php echo $form->error($model,'last_name'); ?>
                  </div>
                  <div class="form-group">
                    <div class="row">
                    	<div class="col-md-4">
                    	<?php echo $form->labelEx($model,'gender'); ?>
                        <?php
                    		$accountStatus = array('Male'=>'Male', 'Female'=>'Female');
                    		echo $form->radioButtonList($model,'gender',$accountStatus,array('class'=>'col-md-4'));
                    	?>
                    	</div>
                    </div>
                  </div>
                  <div class="form-group">
                      <?php echo $form->labelEx($model,'email'); ?>
                      <?php echo $form->textField($model,'email',array('class'=>'form-control','maxlength'=>100,'readonly'=>true)); ?>
                      <?php echo $form->error($model,'email'); ?>
                  </div>
                  <?php if ($model->isNewRecord) { ?> 
                  <div class="form-group">
                      <?php echo $form->labelEx($model,'password'); ?>
                      <?php echo $form->passwordField($model,'password',array('class'=>'form-control','maxlength'=>60)); ?>
                      <?php echo $form->error($model,'password'); ?>
                  </div>
                  <?php } ?>
                <?php if($model->member_type=='dropbox'){?>
                <div class="form-group">
                    <?php echo $form->labelEx($model,'shop_location'); ?>
                    <?php echo $form->textField($model,'shop_location',array('class'=>'form-control','maxlength'=>200)); ?>
                    <?php echo $form->error($model,'shop_location'); ?>
                </div>
                <div class="form-group">
                    <?php echo $form->labelEx($model,'shop_phone'); ?>
                    <?php echo $form->textField($model,'shop_phone',array('class'=>'form-control','maxlength'=>200)); ?>
                    <?php echo $form->error($model,'shop_phone'); ?>
                </div>
        		<?php }?>
                 <div class="form-group">
                    <?php echo $form->labelEx($addressEdtModel,'address'); ?>
                    <?php echo $form->textArea($addressEdtModel,'address',array('class'=>'form-control','maxlength'=>200)); ?>
                    <?php echo $form->error($addressEdtModel,'address'); ?>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-6">
            <div class="box box-danger">
                <div class="box-header with-border"></div>
                    <div class="box-body">
                        <div class="form-group"> 
                        	<div class="row">
                                 <div class="col-md-8">
                                    <?php echo $form->labelEx($addressEdtModel,'city'); ?>
                                    <?php echo $form->textField($addressEdtModel,'city',array('class'=>'form-control','maxlength'=>200)); ?>
                                    <?php echo $form->error($addressEdtModel,'city'); ?>
                                 </div>
                                 <div class="col-md-4">
                                  <?php echo $form->labelEx($addressEdtModel,'state'); ?>
                                  <?php echo $form->textField($addressEdtModel,'state',array('class'=>'form-control','maxlength'=>200)); ?>
                                  <?php echo $form->error($addressEdtModel,'state'); ?>
                                </div>
                             </div>
                             <div class="row">
                                 <div class="col-md-8">
                                  <?php echo $form->labelEx($addressEdtModel,'postal_code'); ?>
                                  <?php echo $form->textField($addressEdtModel,'postal_code',array('class'=>'form-control','maxlength'=>200)); ?>
                                  <?php echo $form->error($addressEdtModel,'postal_code'); ?>
                                 </div>
                                <div class="col-md-4">
                                  <?php echo $form->labelEx($addressEdtModel,'country'); ?>
                                  <?php echo $form->textField($addressEdtModel,'country',array('class'=>'form-control','maxlength'=>200)); ?>
                                  <?php echo $form->error($addressEdtModel,'country'); ?>
                                </div>
                            </div> 
                            <div class="form-group">
                                <?php echo $form->labelEx($model,'profession'); ?>
                                <?php echo $form->textField($model,'profession',array('class'=>'form-control','maxlength'=>200)); ?>
                                <?php echo $form->error($model,'profession'); ?>
                            </div>
                            <?php if($model->member_type!='requester'){?>
                            <div class="form-group">
                                <?php echo $form->labelEx($model,'office_address'); ?>
                                <?php echo $form->textArea($model,'office_address',array('class'=>'form-control','maxlength'=>200)); ?>
                                <?php echo $form->error($model,'office_address'); ?>
                            </div>
                            <div class="form-group">
                                <?php echo $form->labelEx($model,'photo_number'); ?>
                                <?php echo $form->textField($model,'photo_number',array('class'=>'form-control','maxlength'=>200)); ?>
                                <?php echo $form->error($model,'photo_number'); ?>
                            </div>
                            <div class="form-group">
                                <?php echo $form->labelEx($model,'aadhar_number'); ?>
                                <?php echo $form->PasswordField($model,'aadhar_number',array('class'=>'form-control','maxlength'=>200,'data-toggle'=>"password")); ?>
                                <?php echo $form->error($model,'aadhar_number'); ?>
                            </div>
                            <div class="form-group">
                            	<div class="row">
							    	<div class="col-md-4">
							    		<?php echo $form->labelEx($model,'travel_from_to'); ?>
							    		<?php
                                    		$accountStatus = array('Yes'=>'Yes', 'No'=>'No');
                                    		echo $form->radioButtonList($model,'travel_from_to',$accountStatus,array('class'=>'col-md-4'))?>
							        </div>
							    	<div class="col-md-4">
							    		<?php echo $form->labelEx($model,'mode_of_commute'); ?>
                        	             <?php
                                    		$mode_of_commute = array('Car'=>'Car', 'Bus'=>'Bus','Bike'=>'Bike','Train'=>'Train');
                                    		echo $form->radioButtonList($model,'mode_of_commute',$mode_of_commute);
                        	             ?>
							        </div>
            					</div>
                            </div>
                            <?php }?>
                            <div class="form-group">
                                <?php echo $form->labelEx($model,'account_status'); ?>
                                <?php echo $form->textField($model,'account_status',array('class'=>'form-control','maxlength'=>200,'readonly'=>true)); ?>
                                <?php echo $form->error($model,'account_status'); ?>
                            </div>
                            <div class="form-group">
                            	<div class="row">
							    	<div class="col-md-4">
							    		<?php echo $form->labelEx($model,'country_code'); ?>
							    		<?php echo $form->textField($model,'country_code',array('class'=>'form-control','maxlength'=>10)); ?>
							        	<?php echo $form->error($model,'country_code'); ?>
							        </div>
							        <div class="col-md-8">
							        	<?php echo $form->labelEx($model,'phone'); ?>
							    		<?php echo $form->textField($model,'phone',array('class'=>'form-control','maxlength'=>10,'minlength'=>7)); ?>
							        	<?php echo $form->error($model,'phone'); ?>
							        </div>
            					</div>
                            </div>
                            <!-- <div class="form-group">
                              <div class="row">
                                      <div class="col-md-4">
                                        <p class="labl">image : </p> 
                                        <?php if ($model->image != '') { ?>
                                              <img width="65px" height="65px" style="border-radius:40%;" src="<?php echo Yii::app()->params['profileImageBucketUrl'].$model['image']; ?>">
                                        <?php   }elseif ($model->facebook_image != '') { ?>
                                              <img width="65px" height="65px" style="border-radius:40%;" src="<?php echo $model['facebook_image']; ?>">
                                        <?php  }  else {  ?>
                                              <p>choose an image </p>
                                        <?php   }  ?>
                                      </div>  
                                      <div class="col-md-8">
                                          <?php echo $form->label($model, 'image')?>
                                          <?php echo $form->fileField($model, 'image')?>
                                          <?php echo $form->error($model, 'image')?>
                                      </div>
                              </div>
                           </div> -->
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
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-show-password/1.0.3/bootstrap-show-password.min.js"></script>
<script type="text/javascript">
	$("#Users_aadhar_number").password('toggle');
</script>
<?php $this->endWidget(); ?>


