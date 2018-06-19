<?php
$this->breadcrumbs=array(
    'Admin'=>array('customer'),
    'create',
);

?>
<div class="box">
  <div class="box-body">
   <h1>View <?php echo $model->first_name." ".$model->last_name;?></h1>
   <br>
   <hr>
    <?php
    $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
        'id'=>'admin-form',
        'enableAjaxValidation'=>true,
    )); ?>
    <div class="row">
      <div class="col-md-6">
         <div class="box box-primary">
            <div class="box-header with-border"></div>
               <div class="box-body">
    				<div class="form-group">
                      <div class="form-group">
                          <?php echo $form->labelEx($model,'first_name'); ?>
                          <?php echo $form->textField($model,'first_name',array('class'=>'form-control','maxlength'=>150,'disabled'=>true)); ?>
                          <?php echo $form->error($model,'first_name'); ?>
                      </div>
                      <div class="form-group">
                          <?php echo $form->labelEx($model,'address'); ?>
                          <?php echo $form->textArea($model,'address',array('class'=>'form-control','maxlength'=>500,'disabled'=>true)); ?>
                          <?php echo $form->error($model,'address'); ?>
                      </div>
                      <div class="form-group">
                        <div class="row">
                        	<div class="col-md-4">
                        	<?php echo $form->labelEx($model,'gender'); ?>
                            <?php
                        		$accountStatus = array('Male'=>'Male', 'Female'=>'Female');
                        		echo $form->radioButtonList($model,'gender',$accountStatus,array('class'=>'col-md-4','disabled'=>true));
                        	?>
                        	</div>
                        </div>
                      </div>
                      <div class="form-group">
                          <?php echo $form->labelEx($model,'dob'); ?>
                          <?php echo $form->textField($model,'dob',array('class'=>'form-control','maxlength'=>150,'disabled'=>true)); ?>
                          <?php echo $form->error($model,'dob'); ?>
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
    					<div class="form-group">
                          <?php echo $form->labelEx($model,'last_name'); ?>
                          <?php echo $form->textField($model,'last_name',array('class'=>'form-control','maxlength'=>150,'disabled'=>true)); ?>
                          <?php echo $form->error($model,'last_name'); ?>
                      	</div>
                      	<div class="form-group">
                          <?php echo $form->labelEx($model,'phone'); ?>
                          <?php echo $form->textField($model,'phone',array('class'=>'form-control','maxlength'=>150,'disabled'=>true)); ?>
                          <?php echo $form->error($model,'phone'); ?>
                      	</div>
                      	<div class="form-group">
                          <?php echo $form->labelEx($model,'email_id'); ?>
                          <?php echo $form->textField($model,'email_id',array('class'=>'form-control','maxlength'=>150,'disabled'=>true)); ?>
                          <?php echo $form->error($model,'email_id'); ?>
                      </div>
                      <div class="form-group">
                          <?php echo $form->labelEx($model,'username'); ?>
                          <?php echo $form->textField($model,'username',array('class'=>'form-control','maxlength'=>150,'disabled'=>true)); ?>
                          <?php echo $form->error($model,'username'); ?>
                      </div>
                      <div class="form-group">
                          <?php echo $form->labelEx($model,'password'); ?>
                          <?php echo $form->passwordField($model,'password',array('class'=>'form-control','maxlength'=>150,'disabled'=>true)); ?>
                          <?php echo $form->error($model,'password'); ?>
                      </div>
    				</div>
    			</div>
    		</div>
    	</div>			
    </div>
</div>
</div>
<?php $this->endWidget(); ?>