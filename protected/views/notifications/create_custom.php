<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
    'id'=>'push-form',    
)); ?>  
<div class="modal-header">
   <button type="button" class="close" data-dismiss="modal">&times;</button>
   <h4 class="modal-title">Create new push notification</h4>
</div>
<div class="modal-body">     
    <div class="form-group">
      <div class="form-group">
            <?php echo $form->labelEx($model,'user_type',array('style'=>'float:left')); ?>
            <?php echo $form->dropDownList($model, 'user_type', array('requester'=>'Requester','doothan'=>'Doothan','dropbox'=>'Dropbox'),array('class'=>'form-control','empty'=>'Select a Usertype','onChange'=>'ChangeUser(this)'));?>
            <?php echo $form->error($model,'user_type'); ?>
      </div>
      <div class="form-group" id="role_users">
            <?php echo $form->labelEx($model,'user_id',array('style'=>'float:left')); ?>
            <?php echo $form->dropDownList($model, 'user_id', array(),array('class'=>'form-control select2','multiple'=>'multiple','data-placeholder'=>'Select user','required'=>'required'));?>
            <?php echo $form->error($model,'user_id'); ?>
      </div>
      <div class="form-group">
        <?php echo $form->labelEx($model,'message',array('style'=>'float:left')); ?>
        <?php echo $form->textArea($model,'message',array('class'=>'form-control','maxlength'=>200,'required'=>'required')); ?>
        <?php echo $form->error($model,'message'); ?>
      </div>
    </div>
</div>
<div class="modal-footer">
	<span id="error_showing"></span>
	<div style="margin-right: 20%;color:red;display:none;float:left;" id="display_it">
    		<span id="header_msg">Unregistred users :</span>
			<ul style="margin-top: 20px;" id="li_content">
			</ul>
  	</div>
	<?php $this->widget('bootstrap.widgets.TbButton', array(
		'buttonType'=>'submit',
		'type'=>'primary',
	    'id'=>'notify_someone',
		'label'=>$model->isNewRecord ? 'Push Notification' : 'save',
    )); ?>
    <button type="button" class="btn btn-default" id="reset_button" data-dismiss="modal">Close</button>
</div>
<?php $this->endWidget(); ?>
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/vendor/bower_components/select2/dist/css/select2.min.css">
<script src="<?php echo Yii::app()->request->baseUrl; ?>/vendor/bower_components/select2/dist/js/select2.full.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.validate.min.js"></script>
<script type="text/javascript">
$(function () {
	$('.select2').select2()
})

function ChangeUser(param){
	var value = $(param).val();
	$.ajax({
    	type:'POST',
    	dataType:'html',
    	data:{'value':value},
    	url:'<?php echo Yii::app()->createAbsoluteUrl("Notifications/LoadRolesBasedUsers")?>',
    	success:function(response){
    		$('#role_users').html(response);
    		$('.select2').select2();
    	},error: function(jqXHR, textStatus, errorThrown) {
    		//window.location.reload();
        }
    });
}
$( "#push-form" ).submit(function( event ) {
	$('.modal-footer button').html('Please wait..!');
	  event.preventDefault();
	  if($("#push-form").valid()){
    	  $.ajax({
    			type:'POST',
    			data: $('form#push-form').serialize(),
    			url:'<?php echo Yii::app()->createAbsoluteUrl("Notifications/SubmitPush"); ?>',
    			success:function(response){
    				if(response.status=="true"){
                     	if(response.unregistred==''){
                     		$('.modal-footer button').html('Notification Send').attr('disabled',true);
                     		$('#reset_button').html('Close');
                     		$.ajax({
                     	    	type:'POST',
                     	    	dataType:'html',
                     	    	url:'<?php echo Yii::app()->createAbsoluteUrl("Notifications/Custom_render")?>',
                     	    	success:function(response){
                     	    		$('#reset_button').html('Close').attr('disabled',false);
                         	    	$('#reset_button').click();
                     	    		$('.custom_div_content').html(response);
                     	    		$('#row_data').html('<button type="button" class="close" data-dismiss="alert">&times;</button> Notification successfully sent').show();
                     	    	}
                     	  });
                         }else{
                             $('#display_it').show();
                             $("#li_content").html("");
                             $('#header_msg').html("Oops, couldn't find below user(s) device id, try to login again");
                             var str = response.unregistred;
                         	 var array = str.split(',');
                         	 $.each(array, function(index, value) {
                         	  $("#li_content").append("<li>" + value + "</li>");
                         	 });
                         	 $('.modal-footer button').html('Push Notification');
                         	 $('#reset_button').html('Close');
                         }
                         
             		}
    			},error: function(jqXHR, textStatus, errorThrown) {
    				//window.location.reload();
    	        }
    	});
	}
});
</script>