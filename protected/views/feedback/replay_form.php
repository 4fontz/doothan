<?php
$form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'replay-form',
	'enableAjaxValidation'=>true,
    )); ?>
    <?php if($type=="1"){?>
        <div class="modal-header">
           <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">View & Reply to the callback request</h4>
        </div>
      	<div class="modal-body">    
            <div class="form-group">
			<!-- <div class="form-group">	
					<p><b>Callback : </b><?php echo $model->comments; ?></p>
			</div> -->
              <div class="form-group">
                  <?php echo $form->labelEx($model,'comments'); ?>
                  <?php echo $form->textArea($model,'comments',array('class'=>'form-control','maxlength'=>200)); ?>
                  <?php echo $form->error($model,'comments'); ?>
                  <input type="hidden" id="feed_back_id" value="<?php echo $model->id?>" name="Feedback[id]"/>
                  <input type="hidden" id="feed_back_type" value="<?php echo $model->type?>" name="Feedback[type]"/>
              </div>
            </div> 
      	</div>
  	<?php }else{?>
      	<div class="modal-header">
           <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">View & Replay to the feedback request</h4>
        </div>
      	<div class="modal-body">    
            <div class="form-group">
							<div class="form-group">
						 		<p><b>Feedback : </b><?php echo $model->feedback; ?></p>
						 	</div>
              <div class="form-group">
                  <?php echo $form->labelEx($model,'replay'); ?>
                  <?php echo $form->textArea($model,'replay',array('class'=>'form-control','maxlength'=>200)); ?>
                  <?php echo $form->error($model,'replay'); ?>
                  <input type="hidden" id="feed_back_id" value="<?php echo $model->id?>" name="Feedback[id]"/>
                  <input type="hidden" id="feed_back_type" value="<?php echo $type;?>" name="Feedback[type]"/>
              </div>
            </div> 
      	</div>
  	<?php }?>
  	<div class="modal-footer">
  		<span id="error_showing"></span>
    	<?php $this->widget('bootstrap.widgets.TbButton', array(
    		'buttonType'=>'submit',
    		'type'=>'primary',
    		'label'=>$model->isNewRecord ? 'create' : 'save',
    	)); ?>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	</div>
<?php $this->endWidget(); ?>
<script type="text/javascript">
$( "#replay-form" ).submit(function( event ) {
	type="<?php echo $type;?>";
	  event.preventDefault();
	  var id = $('#feed_back_id').val();
	  var flag=false;
	  if(type==1){
		  if($('#Feedback_comments').val()==""){
			  $('#Feedback_comments').css({'border':'1px solid red'});
			  flag=false;
			  return false;
		  }else{
			flag=true;
		  }
	  }else{
		  if($('#Feedback_replay').val()==""){
			  $('#Feedback_replay').css({'border':'1px solid red'});
			  flag=false;
			  return false;
		  }else{
			flag=true;
		  }
	  }
	  if(flag==true){
    	  $.ajax({
    			type:'POST',
    			data: $('form#replay-form').serialize(),
           timeout: 3000,
    			url:'<?php echo Yii::app()->createAbsoluteUrl("Feedback/Updatereplay"); ?>',
    			success:function(response){
    				if(response=="1"){
    					$('.close').click();
    					if(type=="1"){
    						$('#'+id).html($('#Feedback_comments').val());
    					}else{
    						$('#'+id).html($('#Feedback_replay').val());
        				}
    					$('#sm_'+id).html('Closed').removeClass('btn-warning').addClass('btn-success');
    					$('#custom_success').show();
    					value_text = $('.bg-purple').html();
    					$('.bg-purple').html(value_text-1);
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