<?php
$form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'comment-form',
	'enableAjaxValidation'=>true,
    )); ?>
    <div class="modal-header">
       <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">View & update comments to the notification</h4>
    </div>
  	<div class="modal-body">    
        <div class="form-group">
		<div class="form-group">	
				<p><b>Comment : </b><?php echo $model->comments; ?></p>
		</div>
          <div class="form-group">
              <?php echo $form->labelEx($model,'comments'); ?>
              <?php echo $form->textArea($model,'comments',array('class'=>'form-control','maxlength'=>200)); ?>
              <?php echo $form->error($model,'comments'); ?>
              <input type="hidden" id="notifications_id" value="<?php echo $model->id?>" name="Notifications[id]"/>
          </div>
        </div> 
  	</div>
  	<div class="modal-footer">
  		<span id="error_showing"></span>
    	<?php $this->widget('bootstrap.widgets.TbButton', array(
    		'buttonType'=>'submit',
    		'type'=>'primary',
    		'label'=>'Update',
    	)); ?>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	</div>
<?php $this->endWidget(); ?>
<script type="text/javascript">
$( "#comment-form" ).submit(function( event ) {
	type="<?php echo $type;?>";
	  event.preventDefault();
	  var id = $('#notifications_id').val();
	  var flag=false;
	  if($('#Notifications_comments').val()==""){
		  $('#Notifications_comments').css({'border':'1px solid red'});
		  flag=false;
		  return false;
	  }else{
		flag=true;
	  }
	  if(flag==true){
    	  $.ajax({
    			type:'POST',
    			data: $('form#comment-form').serialize(),
           		timeout: 3000,
    			url:'<?php echo Yii::app()->createAbsoluteUrl("Notifications/Updatereplay"); ?>',
    			success:function(response){
    				if(response=="1"){
    					$('.close').click();
    					$('#'+id).html($('#Notifications_comments').val());
    					$('#sm_'+id).html('Closed').removeClass('btn-warning').addClass('btn-success');
    					$('#custom_success').show();
    					value_text = $('#noti_count').html();
    					$('#noti_count').html(value_text-1);
    				}else{
    					$('#error_showing').html('Error while updating comment,Please try after some time..').css({'color':'red'});
    				}
    			},error: function(jqXHR, textStatus, errorThrown) {
    				//window.location.reload();
    	        }
    	});
	}
});
</script>