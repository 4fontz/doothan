<?php
$this->breadcrumbs = array(
    'Payment Management',
);
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
  $('.search-form').toggle();
  return false;
});
$('.search-form form').submit(function(){
  $.fn.yiiGridView.update('users-grid', {
    data: $(this).serialize()
  });
  return false;
});
");
?>
<div class="box">
    <div class="box-body">
<h1>Manage Payment list</h1>
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
             'onChange'=>'ChangePagecount(this,"Request","payments","")',
           )
        );
?>
<div class="table-toolbar">
            <div class="page-size-wrap" style="width:100%">
                <div class="results-perpage">
                    <?= $pageSizeDropDown; ?><label>results per page</label>
                	<input type="button" id="refresh_grid" value="Refresh Grid" style="float:right;padding: 3px 8px;" class="btn btn-primary" onclick="$('#time_showing_space').html('( Refreshed now )');var dNow = new Date();new_mu_date = dNow.getMonth()+1;var localdate= (dNow.getFullYear() + '-' + new_mu_date) + '-' + dNow.getDate() + ' ' + dNow.getHours() + ':' + dNow.getMinutes() + ':' + dNow.getSeconds();$('#time_frame').val(localdate);">
        			<input type="hidden" id="time_frame" value="<?php date_default_timezone_set('Asia/Kolkata');; echo date("Y-m-d H:i:s");?>">
                </div>
                <div class="results-perpage" style="float: right;color:#868686;">
                   <label id="time_showing_space" style="font-size: 11px;font-weight: normal;">( Refreshed now )</label>
                </div>
                
            </div>
            <?php Yii::app()->clientScript->registerCss('initPageSizeCSS', '.page-size-wrap{width: 10%; float: left;}'); ?>
            
        </div>
         <div class="clear"></div>
        <div class="space_10px"></div>
        <div class="clear"></div>
<div class="custom_div_data">
<?php

if ($model->search()) {
    $this->widget('ext.yiisortablemodel.widgets.SortableCGridView', array(
        'id' => 'users-grid',
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
                //'name'=>'request_code_data',
                'header' => 'Request Code',
                'type' => 'raw',
                'value'=>array($model,'RequestCode'),
                'htmlOptions' => array('style' => 'width: 8%'),
                'filter' => CHtml::activeTextField($model, 'request_code_data'),
            ),
            array(
                'name'=>'firstname',
                'header'=> 'User',
                'type' => 'html',
                'value'=> array($model,'FullName'),
                'htmlOptions' => array('style' => 'width: 6%'),
            ),
            array(
                'name'=>'transaction_id',
                'header' => 'Transaction Id',
                'htmlOptions' => array('style' => 'width: 10%'),
            ),
            array(
                'name'=>'amount',
                'header' => 'Amount',
                'type'=>'raw',
                'value'=>array($model,'AmountValue'),
                'htmlOptions' => array('style' => 'width: 8%;text-align: end')
            ),
            array(
                'header' => 'productinfo',
                'value' => array($model,'ProductInfo'),
                'htmlOptions' => array('style' => 'width: 12%'),
            ),
            array(
                'name'=>'mode',
                'header' => 'Mode',
                'htmlOptions' => array('style' => 'width: 10%')
            ),
            array(
                'name'=>'bank_ref_num',
                'header' => 'Bank Refferal Number',
                'htmlOptions' => array('style' => 'width: 10%')
            ),
            array(
                'name'=>'status',
                'header' => 'Status',
                'htmlOptions' => array('style' => 'width: 10%')
            ),
            
            array(
                'name' => 'created_on',
                'header'=>'Created on',
                'value' => array($model,'userJoinedDate'),
                // 'value'=>'Yii::app()->dateFormatter->format("d/m/y h:i",$data->created_on)',
                'htmlOptions' => array('style' => 'width: 10%')
            ),
            array(
                'header' => 'Action',
                'class' => 'ButtonColumn',
                'template' => '{view}',
                'htmlOptions' => array('style' => 'width: 15%','class' => "button-column"),
                'buttons' => array(
                    'view' => array( //the name {reply} must be same
                            'label' => '<i class="icon-remove icon-white"></i> View', // text label of the button
                            'options' => array('class'=>"btn btn-info btn-xs ",'title'=>'View'),
                            'url' => function($data) {
                                $url = Yii::app()->createUrl('request/PaymentDetail/' . $data->id);
                                return $url;
                            }
                   ),
               )
           ),
        ),
    ));

}

?>
</div>

<script type="text/javascript">
    //$(function ($) {
    $.noConflict();
    $(document).ready(function(){
        $(document).on("change", '#pageSize', function () {
        	$.fn.yiiGridView.update('users-grid', {data: {pageSize: $(this).val()}});
        });
    });
   //});
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








