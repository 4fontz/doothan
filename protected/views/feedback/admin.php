<?php
$this->breadcrumbs = array(
    'Feedback management',
);
$this->menu = array(
    array('label' => 'List Requestors', 'url' => array('index')),
    array('label' => 'Create Requestors', 'url' => array('create')),
);
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
  $('.search-form').toggle();
  return false;
});
$('.search-form form').submit(function(){
  $.fn.yiiGridView.update('feedback-grid', {
    data: $(this).serialize()
  });
  return false;
});
");
?>
<div id="myModalContent" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Feedback Text</h4>
      </div>
      <div class="modal-body" id="modal-body">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
<div class="box">
    <div class="box-body">
<h1>Manage Feedback list</h1>
<div class="alert alert-success" id="custom_success" style="display:none;">
   <i class="fa fa-check" aria-hidden="true"></i> 
      Reply has been successfully updated against the feedback request
</div>
<?php if (Yii::app()->user->hasFlash('success')): ?>
    <div class="alert alert-success">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <?php echo Yii::app()->user->getFlash('success'); ?>
    </div>
<?php endif; ?>
<?php if (Yii::app()->user->hasFlash('error')): ?>
    <div class="alert alert-error">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <?php echo Yii::app()->user->getFlash('error'); ?>
    </div>
<?php endif; ?>
<br/>
<hr>
<?php
        $pageSize = Yii::app()->user->getState('pageSize', Yii::app()->params['defaultPageSize']);
        $pageSizeDropDown = CHtml::dropDownList(
                        'pageSize', $pageSize, array(10 => 10, 15 => 15, 30 => 30, -1 => 'All'), array(
                         'id' => 'pageSize',
                         'onChange'=>'ChangePagecount(this,"Feedback","index","")',
                        )
           );
?>
<div class="table-toolbar">
<div class="page-size-wrap" style="width:100%">
    <div class="results-perpage">
        <?= $pageSizeDropDown; ?><label>results per page</label>
        <input type="button" id="refresh_grid" value="Refresh Grid" style="float:right;padding: 3px 8px;" class="btn btn-primary" onclick="$('#time_showing_space').html('( Refreshed now )');var dNow = new Date();new_mu_date = dNow.getMonth()+1;var localdate= (dNow.getFullYear() + '-' + new_mu_date) + '-' + dNow.getDate() + ' ' + dNow.getHours() + ':' + dNow.getMinutes() + ':' + dNow.getSeconds();$('#time_frame').val(localdate);$.fn.yiiGridView.update('feedback-grid', {data: {pageSize: $(this).val()}});">
		<input type="hidden" id="time_frame" value="<?php date_default_timezone_set('Asia/Kolkata'); echo date("Y-m-d H:i:s");?>">
    </div>
    <div class="results-perpage" style="float: right;color:#868686;">
       <label id="time_showing_space" style="font-size: 11px;font-weight: normal;">( Refreshed now )</label>
    </div>
    
</div>
<?php Yii::app()->clientScript->registerCss('initPageSizeCSS', '.page-size-wrap{width: 10%; float: left;}'); ?>            
</div>
<div class="custom_div_data">
<div class="clear"></div>
<div class="space_10px"></div>
<div class="clear"></div>
<?php
$this->widget('ext.yiisortablemodel.widgets.SortableCGridView', array(
    'id' => 'feedback-grid',
    'dataProvider' => $model->search(),
    'summaryText' => "{start} - {end} of {count}",
    'ajaxUpdate' => true,
    'enableDragDropSorting'=>false,
    //'orderField' => 'id',
    'idField' => 'id',
    'filter' => $model,
    'htmlOptions' => array('class' => 'span12 table-responsive'),
    'itemsCssClass' => 'table',
    'columns' => array(
        array(
            'name'=>'id',
            'header' => 'Id',
            'htmlOptions' => array('style' => 'width: 4%')
        ),
        array(
            'name'=>'user_id',
            'header'=> 'User',
            'type' => 'html',
            'value'=> array($model,'FullName'),
            'htmlOptions' => array('style' => 'width: 10%'),
        ),
        array(
            'name'=>'feedback',
            'value'=>array($model,'FeedbackText'),
            'htmlOptions' => array('style' => 'width: 15%'),
        ),
        
        array(
            'name' => 'created_at',
            'value' => array($model,'userJoinedDate'),
            // 'value'=>'Yii::app()->dateFormatter->format("m/d/y",$data->created)',
            'htmlOptions' => array('style' => 'width: 15%')
        ),
        array(
            'name'=>'comments',
            'header'=> 'Action',
            'type' => 'raw',
            'filter'=>'',
            'value'=> array($model,'ViewMoreContent'),
            'htmlOptions' => array('style' => 'width: 6%'),
        ),
        array(
            'name'=>'replay',
            'header'=> 'Replay',
            'type' => 'raw',
            'filter'=>false,
            'value'=> array($model,'UpdateReplay'),
            'htmlOptions' => array('style' => 'width: 6%'),
        ),
    ),
));
?>
</div>

<script type="text/javascript">
function show_replay_form(param){
	var id=$(param).attr('id');
	type="0";
	$.ajax({
		type:'POST',
		dataType:'html',
		data:{'id':id,'type':type},
		url:'<?php echo Yii::app()->createAbsoluteUrl("Feedback/Updatereplayform"); ?>',
		success:function(response){
			$('.modal-content').html(response);
		},error: function(jqXHR, textStatus, errorThrown) {
			//window.location.reload();
        }
	});
}

jQuery(function ($) {
    jQuery(document).on("change", '#pageSize', function () {
        $.fn.yiiGridView.update('users-grid', {data: {pageSize: $(this).val()}});
    });
   
});


function show_more_content(param){
	text = $(param).attr('id');
	$('#modal-body').html('<p>'+text+'</p>');
} 
window.setInterval(Getinterval, 5000);
function Getinterval(tim){
	var saved_time  = $('#time_frame').val();
    var dNow = new Date();
    new_mu_date = dNow.getMonth()+1;
    var localdate= (dNow.getFullYear() + '-' + new_mu_date) + '-' + dNow.getDate() + ' ' + dNow.getHours() + ':' + dNow.getMinutes() + ':' + dNow.getSeconds();
	var seconds = datediff(saved_time, localdate, 'seconds') % 60;
	var minutes = datediff(saved_time, localdate, 'minutes') % 60;
	var hours = datediff(saved_time, localdate, 'hours');
	if(hours==0){
		if(minutes==0){
			time = seconds + " seconds ";
		}else{
			time = minutes + " minutes " + seconds + " seconds ";
		}
	}else{
		time = hours + " hours" + minutes + " minutes" + seconds + " seconds ";
	}
	$('#time_showing_space').html('( Refreshed '+time+' ago )');
}

function datediff(fromDate,toDate,interval) { 
	  var second=1000, minute=second*60, hour=minute*60, day=hour*24, week=day*7; 
	  fromDate = new Date(fromDate); 
	  toDate = new Date(toDate); 
	  var timediff = toDate - fromDate; 

	  if (isNaN(timediff)) return NaN; 
	  switch (interval) { 
	    case "years": return toDate.getFullYear() - fromDate.getFullYear(); 
	    case "months": return ( 
	      ( toDate.getFullYear() * 12 + toDate.getMonth() ) 
	      - 
	      ( fromDate.getFullYear() * 12 + fromDate.getMonth() ) 
	    ); 
	    case "weeks"  : return Math.floor(timediff / week); 
	    case "days"   : return Math.floor(timediff / day);  
	    case "hours"  : return Math.floor(timediff / hour);  
	    case "minutes": return Math.floor(timediff / minute); 
	    case "seconds": return Math.floor(timediff / second); 
	    default: return undefined; 
	  } 
}
</script> 
</div>
</div>