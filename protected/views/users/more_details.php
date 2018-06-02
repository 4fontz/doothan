
	<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
    	'id'=>'users-form',
    	'enableAjaxValidation'=>true,
    	'htmlOptions'=>array('enctype'=>'multipart/form-data'),
    
    )); ?>
	<div class="row">
        <div class="col-md-6">
          <div class="">
            <div class="box-header with-border"></div>
              <div class="box-body">
                <div class="form-group">
                  <div class="form-group">
                     <?php echo $form->labelEx($basic_model,'first_name'); ?>
                  	 <?php echo $form->textField($basic_model,'first_name',array('class'=>'form-control class-small','maxlength'=>100,'disabled'=>true)); ?>
                  	 <?php echo $form->error($basic_model,'first_name'); ?>
                  </div>
                  <div class="form-group">
                      <?php echo $form->labelEx($basic_model,'last_name'); ?>
                      <?php echo $form->textField($basic_model,'last_name',array('class'=>'form-control class-small','maxlength'=>100,'disabled'=>true)); ?>
                      <?php echo $form->error($basic_model,'last_name'); ?>
                  </div>
                  
                  <div class="form-group">
                      <?php echo $form->labelEx($basic_model,'email'); ?>
                      <?php echo $form->textField($basic_model,'email',array('class'=>'form-control class-small','maxlength'=>100,'disabled'=>true)); ?>
                      <?php echo $form->error($basic_model,'email'); ?>
                  </div>
                  
                  <div class="form-group">
                       <?php echo $form->labelEx($addressEdtModel,'address'); ?>
                       <?php echo $form->textArea($addressEdtModel,'address',array('class'=>'form-control class-small','maxlength'=>200,'disabled'=>true)); ?>
                       <?php echo $form->error($addressEdtModel,'address'); ?>
                  </div>
                  
                  <div class="form-group">
                       	<?php echo $form->labelEx($addressEdtModel,'city'); ?>
                        <?php echo $form->textField($addressEdtModel,'city',array('class'=>'form-control class-small','maxlength'=>200,'disabled'=>true)); ?>
                        <?php echo $form->error($addressEdtModel,'city'); ?>
              	 </div>
              	 <?php if($basic_model->member_type=='dropbox'){
              	     $working_hours = $basic_model->working_hours;
              	 ?>
        		<div class="form-group">
                    <?php echo $form->labelEx($basic_model,'working_hours'); ?>
                    <?php echo $form->textField($basic_model,'working_hours',array('class'=>'form-control class-small','maxlength'=>200,'readonly'=>true,'value'=>$working_hours)); ?>
                    <?php echo $form->error($basic_model,'working_hours'); ?>
                </div>
                <div class="form-group">
                    <?php echo $form->labelEx($basic_model,'shop_location'); ?>
                    <?php echo $form->textField($basic_model,'shop_location',array('class'=>'form-control class-small','maxlength'=>200,'readonly'=>true)); ?>
                    <?php echo $form->error($basic_model,'shop_location'); ?>
                </div>
                <div class="form-group">
                    <?php echo $form->labelEx($basic_model,'shop_phone'); ?>
                    <?php echo $form->textField($basic_model,'shop_phone',array('class'=>'form-control class-small','maxlength'=>200,'readonly'=>true)); ?>
                    <?php echo $form->error($basic_model,'shop_phone'); ?>
                </div>
        		<?php }?>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-6">
            <div class="">
                    <div class="box-body">
                        <div class="form-group">  
                            <div class="form-group">
                                <?php echo $form->labelEx($basic_model,'office_address'); ?>
                                <?php echo $form->textArea($basic_model,'office_address',array('class'=>'form-control class-small','maxlength'=>200,'disabled'=>true)); ?>
                                <?php echo $form->error($basic_model,'office_address'); ?>
                            </div>
                            <?php if($basic_model->member_type!='requester'){?>
                            <div class="form-group">
                                <?php echo $form->labelEx($basic_model,'photo_number'); ?>
                                <?php echo $form->textField($basic_model,'photo_number',array('class'=>'form-control class-small','maxlength'=>200,'readonly'=>true)); ?>
                                <?php echo $form->error($basic_model,'photo_number'); ?>
                            </div>
                            <div class="form-group">
                                <?php echo $form->labelEx($basic_model,'aadhar_number'); ?>
                                <?php echo $form->PasswordField($basic_model,'aadhar_number',array('class'=>'form-control class-small','maxlength'=>200,'readonly'=>true,'data-toggle'=>"password")); ?>
                                <?php echo $form->error($basic_model,'aadhar_number'); ?>
                            </div>
                            <div class="form-group">
                            	<div class="row">
							    	<div class="col-md-4">
							    		<?php echo $form->labelEx($basic_model,'travel_from_to'); ?>
							    		<?php
                                    		$accountStatus = array('Yes'=>'Yes', 'No'=>'No');
                                    		echo $form->radioButtonList($basic_model,'travel_from_to',$accountStatus,array('class'=>'col-md-4','disabled'=>true))?>
							        </div>
							    	<div class="col-md-4">
							    		<?php echo $form->labelEx($basic_model,'mode_of_commute'); ?>
							    		<?php
                                    		$accountStatus = array('Car'=>'Car', 'Bus'=>'Bus','Bike'=>'Bike','Train'=>'Train');
                                    		echo $form->radioButtonList($basic_model,'mode_of_commute',$accountStatus,array('disabled'=>true));
                        	             ?>
							        </div>
            					</div>
                            </div>
                    		<?php }?>
                            <div class="form-group">
                                <?php echo $form->labelEx($basic_model,'account_status'); ?>
                                <?php echo $form->textField($basic_model,'account_status',array('class'=>'form-control class-small','maxlength'=>200,'readonly'=>true)); ?>
                                <?php echo $form->error($basic_model,'account_status'); ?>
                            </div>
                            <div class="form-group">
                            	<div class="row">
							    	<div class="col-md-4">
							    		<?php echo $form->labelEx($basic_model,'country_code'); ?>
							    		<?php echo $form->textField($basic_model,'country_code',array('class'=>'form-control class-small','maxlength'=>10,'disabled'=>true)); ?>
							        	<?php echo $form->error($basic_model,'country_code'); ?>
							        </div>
							        <div class="col-md-8">
							        	<?php echo $form->labelEx($basic_model,'phone'); ?>
							    		<?php echo $form->textField($basic_model,'phone',array('class'=>'form-control','maxlength'=>10,'minlength'=>7,'disabled'=>true)); ?>
							        	<?php echo $form->error($basic_model,'phone'); ?>
							        </div>
            					</div>
                            </div>
                            <div class="form-group">
                                <?php echo $form->labelEx($addressEdtModel,'state'); ?>
                                <?php echo $form->textField($addressEdtModel,'state',array('class'=>'form-control class-small','maxlength'=>200,'disabled'=>true)); ?>
                                <?php echo $form->error($addressEdtModel,'state'); ?>
                         	</div>
                         	<div class="row">
                                <div class="col-md-4">
                                      <?php echo $form->labelEx($addressEdtModel,'postal_code'); ?>
                                      <?php echo $form->textField($addressEdtModel,'postal_code',array('class'=>'form-control class-small','maxlength'=>200,'disabled'=>true)); ?>
                                      <?php echo $form->error($addressEdtModel,'postal_code'); ?>
                                </div>
                                <div class="col-md-8">
                                  	<?php echo $form->labelEx($basic_model,'profession'); ?>
                                    <?php echo $form->textField($basic_model,'profession',array('class'=>'form-control class-small','maxlength'=>200,'disabled'=>true)); ?>
                                    <?php echo $form->error($basic_model,'profession'); ?>
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