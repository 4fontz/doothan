<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
    'id'=>'users-form',
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
                    		<?php echo $form->labelEx($model,'from_name'); ?>
                    		<?php echo $form->textField($model,'from_name',array('class'=>'form-control','size'=>60,'maxlength'=>150)); ?>
                    		<?php echo $form->error($model,'from_name'); ?>
                    </div>
                	
                	<div class="form-group">
                		<?php echo $form->labelEx($model,'gst'); ?>
                		<?php echo $form->textField($model,'gst',array('class'=>'form-control')); ?>
                		<?php echo $form->error($model,'gst'); ?>
                	</div>
                	
                	<div class="form-group">
                    		<?php echo $form->labelEx($model,'Rate/Km'); ?>
                    		<?php echo $form->textField($model,'default_distance_charge',array('class'=>'form-control')); ?>
                    		<?php echo $form->error($model,'default_distance_charge'); ?>
                    </div>
                   	
                	<div class="form-group">
                    	<label for="Settings_doothan_avail_time">Doothan availability time</label>
                       	<div id="datetimepickerDate" class="input-group timerange">
                            	<?php echo $form->textField($model,'doothan_avail_time',array('class'=>'form-control myclass','id'=>'availability_time')); ?>
                              	<span class="input-group-addon" style=""><i aria-hidden="true" class="fa fa-calendar"></i></span>
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
                    		<?php echo $form->labelEx($model,'from_mail'); ?>
                    		<?php echo $form->textField($model,'from_mail',array('class'=>'form-control class-small','size'=>60,'maxlength'=>100)); ?>
                    		<?php echo $form->error($model,'from_mail'); ?>
                    	</div>
                    	                    
                    	<div class="form-group">
                    		<?php echo $form->labelEx($model,'default_weight_charge'); ?>
                    		<?php echo $form->textField($model,'default_weight_charge',array('class'=>'form-control class-small')); ?>
                    		<?php echo $form->error($model,'default_weight_charge'); ?>
                    	</div>
                    
                    	<div class="form-group">
                    		<?php echo $form->labelEx($model,'minimum_distance_doothan_dropbox'); ?>
                    		<?php echo $form->textField($model,'minimum_distance_doothan_dropbox',array('class'=>'form-control class-small')); ?>
                    		<?php echo $form->error($model,'minimum_distance_doothan_dropbox'); ?>
                   		</div>
                    	<div class="form-group">
                    		<?php echo $form->labelEx($model,'minimum_km'); ?>
                    		<?php echo $form->textField($model,'minimum_km',array('class'=>'form-control class-small')); ?>
                    		<?php echo $form->error($model,'minimum_km'); ?>
                   		</div>

        				<br/>
                		<div class="form-group">
                    		<?php $this->widget('bootstrap.widgets.TbButton', array(
            					'buttonType'=>'submit',
            					'type'=>'primary',
            					'label'=>$model->isNewRecord ? 'Update' : 'save',
            				)); ?>
    					</div>
            		</div>
             </div>
         </div>
       </div>
</section>
<style>

.timerangepicker-container {
  display:flex;
  position: absolute;
}
.timerangepicker-label {
  display: block;
  line-height: 2em;
  background-color: #c8c8c880;
  padding-left: 1em;
  border-bottom: 1px solid grey;
  margin-bottom: 0.75em;
}

.timerangepicker-from,
.timerangepicker-to {
  border: 1px solid grey;
  padding-bottom: 0.75em;
}
.timerangepicker-from {
  border-right: none;
}
.timerangepicker-display {
  box-sizing: border-box;
  display: inline-block;
  width: 2.5em;
  height: 2.5em;
  border: 1px solid grey;
  line-height: 2.5em;
  text-align: center;
  position: relative;
  margin: 1em 0.175em;
}
.timerangepicker-display .increment,
.timerangepicker-display .decrement {
  cursor: pointer;
  position: absolute;
  font-size: 1.5em;
  width: 1.5em;
  text-align: center;
  left: 0;
}

.timerangepicker-display .increment {
  margin-top: -0.25em;
  top: -1em;
}

.timerangepicker-display .decrement {
  margin-bottom: -0.25em;
  bottom: -1em;
}

.timerangepicker-display.hour {
  margin-left: 1em;
}
.timerangepicker-display.period {
  margin-right: 1em;
}
</style>
<script type="text/javascript">
$('.timerange').on('click', function(e) {
    e.stopPropagation();
    var input = $(this).find('input.myclass');

    var now = new Date();
    var hours = now.getHours();
    var period = "PM";
    if (hours < 12) {
      period = "AM";
    } else {
      hours = hours - 11;
    }
    var minutes = now.getMinutes();

    var range = {
      from: {
        hour: hours,
        minute: minutes,
        period: period
      },
      to: {
        hour: hours,
        minute: minutes,
        period: period
      }
    };

    if (input.val() !== "") {
      var timerange = input.val();
      var matches = timerange.match(/([0-9]{2}):([0-9]{2}) (\bAM\b|\bPM\b)-([0-9]{2}):([0-9]{2}) (\bAM\b|\bPM\b)/);
      if( matches.length === 7) {
        range = {
          from: {
            hour: matches[1],
            minute: matches[2],
            period: matches[3]
          },
          to: {
            hour: matches[4],
            minute: matches[5],
            period: matches[6]
          }
        }
      }
    };
    console.log(range);

    var html = '<div class="timerangepicker-container">'+
      '<div class="timerangepicker-from">'+
      '<label class="timerangepicker-label">From:</label>' +
      '<div class="timerangepicker-display hour">' +
          '<span class="increment fa fa-angle-up"></span>' +
          '<span class="value">'+('0' + range.from.hour).substr(-2)+'</span>' +
          '<span class="decrement fa fa-angle-down"></span>' +
      '</div>' +
      ':' +
      '<div class="timerangepicker-display minute">' +
          '<span class="increment fa fa-angle-up"></span>' +
          '<span class="value">'+('0' + range.from.minute).substr(-2)+'</span>' +
          '<span class="decrement fa fa-angle-down"></span>' +
      '</div>' +
      ':' +
      '<div class="timerangepicker-display period">' +
          '<span class="increment fa fa-angle-up"></span>' +
          '<span class="value">PM</span>' +
          '<span class="decrement fa fa-angle-down"></span>' +
      '</div>' +
      '</div>' +
      '<div class="timerangepicker-to">' +
      '<label class="timerangepicker-label">To:</label>' +
      '<div class="timerangepicker-display hour">' +
          '<span class="increment fa fa-angle-up"></span>' +
          '<span class="value">'+('0' + range.to.hour).substr(-2)+'</span>' +
          '<span class="decrement fa fa-angle-down"></span>' +
      '</div>' +
      ':' +
      '<div class="timerangepicker-display minute">' +
          '<span class="increment fa fa-angle-up"></span>' +
          '<span class="value">'+('0' + range.to.minute).substr(-2)+'</span>' +
          '<span class="decrement fa fa-angle-down"></span>' +
      '</div>' +
      ':' +
      '<div class="timerangepicker-display period">' +
          '<span class="increment fa fa-angle-up"></span>' +
          '<span class="value">PM</span>' +
          '<span class="decrement fa fa-angle-down"></span>' +
      '</div>' +
      '</div>' +
    '</div>';

    $(html).insertAfter(this);
    $('.timerangepicker-container').on(
      'click',
      '.timerangepicker-display.hour .increment',
      function(){
        var value = $(this).siblings('.value');
        value.text(
          increment(value.text(), 12, 1, 2)
        );
      }
    );

    $('.timerangepicker-container').on(
      'click',
      '.timerangepicker-display.hour .decrement',
      function(){
        var value = $(this).siblings('.value');
        value.text(
          decrement(value.text(), 12, 1, 2)
        );
      }
    );

    $('.timerangepicker-container').on(
      'click',
      '.timerangepicker-display.minute .increment',
      function(){
        var value = $(this).siblings('.value');
        value.text(
          increment(value.text(), 59, 0 , 2)
        );
      }
    );

    $('.timerangepicker-container').on(
      'click',
      '.timerangepicker-display.minute .decrement',
      function(){
        var value = $(this).siblings('.value');
        value.text(
          decrement(value.text(), 12, 1, 2)
        );
      }
    );

    $('.timerangepicker-container').on(
      'click',
      '.timerangepicker-display.period .increment, .timerangepicker-display.period .decrement',
      function(){
        var value = $(this).siblings('.value');
        var next = value.text() == "PM" ? "AM" : "PM";
        value.text(next);
      }
    );

  });

  $(document).on('click', e => {

    if(!$(e.target).closest('.timerangepicker-container').length) {
      if($('.timerangepicker-container').is(":visible")) {
        var timerangeContainer = $('.timerangepicker-container');
        if(timerangeContainer.length > 0) {
          var timeRange = {
            from: {
              hour: timerangeContainer.find('.value')[0].innerText,
              minute: timerangeContainer.find('.value')[1].innerText,
              period: timerangeContainer.find('.value')[2].innerText
            },
            to: {
              hour: timerangeContainer.find('.value')[3].innerText,
              minute: timerangeContainer.find('.value')[4].innerText,
              period: timerangeContainer.find('.value')[5].innerText
            },
          };

          timerangeContainer.parent().find('input.myclass').val(
            timeRange.from.hour+":"+
            timeRange.from.minute+" "+    
            timeRange.from.period+"-"+
            timeRange.to.hour+":"+
            timeRange.to.minute+" "+
            timeRange.to.period
          );
          timerangeContainer.remove();
        }
      }
    }
    
  });

  function increment(value, max, min, size) {
    var intValue = parseInt(value);
    if (intValue == max) {
      return ('0' + min).substr(-size);
    } else {
      var next = intValue + 1;
      return ('0' + next).substr(-size);
    }
  }

  function decrement(value, max, min, size) {
    var intValue = parseInt(value);
    if (intValue == min) {
      return ('0' + max).substr(-size);
    } else {
      var next = intValue - 1;
      return ('0' + next).substr(-size);
    }
  }
</script>
<?php $this->endWidget(); ?>