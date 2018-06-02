<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'payment_confirm_form',
)); ?>
<div class="modal-header">
   <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title">Pay Now - <?php echo $userData->first_name;?></h4>
</div>
<div class="modal-body">    
    <div class="form-group">
      <div class="form-group">
            <?php echo $form->labelEx($model,'request_id'); ?>
            <?php 
            if($userData->member_type=="doothan"){
                echo $form->dropDownList($model, 'request_id', CHtml::listData(Request::model()->findAllByAttributes(array('doothan_id'=>$userData->id)), 'id', 'request_code'),array('class'=>'form-control select2','multiple'=>'multiple','data-placeholder'=>'Select an order'));
            }else{
                echo $form->dropDownList($model, 'request_id', CHtml::listData(Request::model()->findAllByAttributes(array('dropbox_id'=>$userData->id)), 'id', 'request_code'),array('class'=>'form-control select2','multiple'=>'multiple','data-placeholder'=>'Select an order'));
            }
            ?>
            <?php echo $form->error($model,'request_id'); ?>
      </div>
      <div class="form-group">
		<div class="col-md-12" style="padding-left: 0px;"><?php echo $form->labelEx($model,'mode'); ?></div>
		<div class="col-md-12" style="padding-left: 0px;">
	    	<div class="col-md-4" style="padding-left: 0px;">
	    		<?php
            		$accountStatus = array(0=>'Cash');
            		echo $form->radioButtonList($model,'mode',$accountStatus,array('class'=>'col-md-4','onClick'=>'getValue(this)'))?>
	        </div>
	    	<div class="col-md-4" style="padding-left: 0px;">
	    		<?php
            		$accountStatus = array(1=>'Cheque');
            		echo $form->radioButtonList($model,'mode',$accountStatus,array('class'=>'col-md-4','onClick'=>'getValue(this)'));
	             ?>
	        </div>
        </div>
      </div>
      <div class="form-group" style="display:none;" id="enable_option">
          <?php echo $form->labelEx($model,'cheque_no',array('id'=>'check_no_id')); ?>
          <?php echo $form->textField($model,'cheque_no',array('class'=>'form-control','maxlength'=>200,'autocomplete'=>'off')); ?>
          <?php echo $form->error($model,'cheque_no'); ?>
      </div>
      <div class="form-group">
          <?php echo $form->labelEx($model,'description'); ?>
          <?php echo $form->textArea($model,'description',array('class'=>'form-control','maxlength'=>200)); ?>
          <?php echo $form->error($model,'description'); ?>
      </div>
      <div class="form-group">
          <?php echo $form->labelEx($model,'amount'); ?>
          <?php echo $form->textField($model,'amount',array('class'=>'form-control','maxlength'=>200,'autocomplete'=>'off')); ?>
          <?php echo $form->hiddenField($model,'user_id',array('class'=>'form-control','maxlength'=>200,'value'=>$userData->id)); ?>
          <?php echo $form->error($model,'amount'); ?>
      </div>
    </div> 
</div>
<div class="modal-footer">
	<span id="error_showing"></span>
	<?php $this->widget('bootstrap.widgets.TbButton', array(
		'buttonType'=>'submit',
		'type'=>'primary',
		'label'=>$model->isNewRecord ? 'Create' : 'Save',
	)); ?>
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>
<?php $this->endWidget(); ?>
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/vendor/bower_components/select2/dist/css/select2.min.css">
<script src="<?php echo Yii::app()->request->baseUrl; ?>/vendor/bower_components/select2/dist/js/select2.full.min.js"></script>
<script type="text/javascript">
$(function () {
	$('.select2').select2()
})

function getValue(param){
	if($(param).val()=="1"){
		$('#enable_option').show();
		$('#enable_option.label').addClass('required');
		$('#check_no_id').html('Cheque No <span class="required">*</span>');
	}else{
		$('#enable_option').hide();
	}
}

$( "#payment_confirm_form" ).submit(function( event ) {
	  event.preventDefault();
    	  $.ajax({
    			type:'POST',
    			data: $('#payment_confirm_form').serialize(),
    			url:'<?php echo Yii::app()->createAbsoluteUrl("Users/PayNow"); ?>',
    			success:function(response){
    				if(response=="1"){
    					$('.close').click();
						var id="<?php echo $userData->id;?>";
    					$.ajax({
    						type:'POST',
    						dataType:'html',
    						data:{'user_id':id},
    						url:'<?php echo Yii::app()->createAbsoluteUrl("Users/afterupdatefee"); ?>',
    						success:function(response){
    							$('#payments').html(response);
    							$('#custom_flash').html('Amount paid successfully<button type="button" class="close" data-dismiss="alert">&times;</button>').addClass('alert alert-success');return false;
    						},error: function(jqXHR, textStatus, errorThrown) {
    							$('#error_showing').html('Oops..please fill the fields first  ').css({'color':'red'});return false;
    				        }
    					});
    				}else{ 
    					$('#error_showing').html('Oops..Some error occured..! ').css({'color':'red'});
        			}
    			},error: function(jqXHR, textStatus, errorThrown) {
    				$('#error_showing').html('Oops..some error occured  ').css({'color':'red'});
    	        }
    	});
});
</script>