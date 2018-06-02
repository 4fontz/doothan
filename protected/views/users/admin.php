<?php
$this->breadcrumbs = array(
    $model->user_title.' management',
);
$this->menu = array(
    array('label' => 'List Requestors', 'url' => array('index')),
    array('label' => 'Create Requestors', 'url' => array('create')),
);

?>
<div class="box">
    <div class="box-body">
<h1>Manage <?php echo $model->user_title;?> list</h1>
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
                        'pageSize', $pageSize, array(10 => 10, 15 => 15, 30 => 30, -1 => 'All'), 
                        array(
                            'id' => 'pageSize',
                           'onChange'=>'ChangePagecount_user(this,"Users","index","'.$model->member_type.'")',
                        )
        );
?>
<div class="table-toolbar">
    <div class="page-size-wrap" style="width:100%">
        <div class="results-perpage">
            <?= $pageSizeDropDown; ?><label>results per page</label>
            <input type="button" id="refresh_grid" value="Refresh Grid" style="float:right;padding: 3px 8px;" class="btn btn-primary" onclick="$('#time_showing_space').html('( Refreshed now )');var dNow = new Date();new_mu_date = dNow.getMonth()+1;var localdate= (dNow.getFullYear() + '-' + new_mu_date) + '-' + dNow.getDate() + ' ' + dNow.getHours() + ':' + dNow.getMinutes() + ':' + dNow.getSeconds();$('#time_frame').val(localdate);$.fn.yiiGridView.update('users-grid', {data: {pageSize: $(this).val()}});">
        	<input type="hidden" id="time_frame" value="<?php date_default_timezone_set('Asia/Kolkata'); echo date("Y-m-d H:i:s");?>">
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
if($model->member_type=="requester"){
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
            'name'=>'first_name',
            'header'=>'Full Name',
            'value'=>array($model,'FullName'),
            'htmlOptions' => array('style' => 'width: 6%'),
        ),
        array(
            'name'=>'email',
            'htmlOptions' => array('style' => 'width: 5%')
        ),
        array(
            'name'=>'phone',
            'htmlOptions' => array('style' => 'width: 5%'),
        ),
        array(
            'header' => 'Role change request',
            'name'=>'role_change_to',
            'type'=>'raw',
            'value'=> array($model,'RoleChangeRequest'),
            'htmlOptions' => array('style' => 'width: 5%')
        ),
        array(
            'name'=>'status',
            'type'=>'raw',
            'value'=> function ($data){
            if($data->status==2)
               return "<a class='btn btn-success btn-xs' href='" . Yii::app()->createAbsoluteUrl('Users/banned', array('id' => $data->id)) . "'>Active</a>";
            else if($data->status==1)
               return "<a class='btn btn-warning btn-xs' href='" . Yii::app()->createAbsoluteUrl('Users/activate', array('id' => $data->id)) . "'>Inactive</a>";
            else 
              return "<a class='btn btn-danger btn-xs' href='" . Yii::app()->createAbsoluteUrl('Users/deactivate', array('id' => $data->id)) . "'>Banned</a>";
            },
            'htmlOptions' => array('style' => 'width: 4%')
        ),
        array(
            'header' => 'Account Status',
            'name'=>'account_status',
            'type'=>'raw',
            'value'=> array($model,'CheckAccountStatus'),
            'htmlOptions' => array('style' => 'width: 8%')
        ),
        array(
            'name' => 'created',
            'value' => array($model,'userJoinedDate'),
            // 'value'=>'Yii::app()->dateFormatter->format("m/d/y",$data->created)',
            'htmlOptions' => array('style' => 'width: 8%')
        ),
        
        array(
            'header' => 'Action',
            'class' => 'ButtonColumn',
            'template' => '{update}{delete}{view}{docs}',
            'htmlOptions' => array('style' => 'width: 10%','class' => "button-column"),
            'buttons' => array(
                'update' => array(
                    'label' => '<i class="icon-pencil icon-white"></i> Edit', // text label of the button
                    'options' => array('class' => "btn btn-primary btn-xs", 'title' => 'Update','style'=>'margin-right:0px'),
                    'url' => function($data) {
                        $url = Yii::app()->createUrl('users/update/' . $data->id);
                        return $url;
                    },
                    'visible'=>'$data->user_address->address!=""',
                ),
                'delete' => array(//the name {reply} must be same
                    'label' => '<i class="icon-remove icon-white"></i> Delete', // text label of the button
                    'options' => array('class' => "btn btn-danger btn-xs delete", 'title' => 'Delete','style'=>'margin-right:0px','id'=>$data->id),
                    'url' => function($data) {
                        $url = Yii::app()->createUrl('users/customerDelete/' . $data->id);
                        return $url;
                    }
                ),
                'view' => array( //the name {reply} must be same
                    'label' => '<i class="icon-remove icon-white"></i> View', // text label of the button
                    'options' => array('class'=>"btn btn-info btn-xs ",'title'=>'View'),
                    'url' => function($data) {
                         $url = Yii::app()->createUrl('users/customerView?id=' . $data->id);
                         return $url;
                    },
                    'visible'=>'$data->user_address->address!=""',
                ),
                'docs' => array( //the name {reply} must be same
                    'label' => '<i class="icon-remove icon-white"></i> Docs', // text label of the button
                    'options' => array('class'=>"btn btn-success btn-xs ",'title'=>'Docs','style'=>'margin-left: 0px'),
                    'url' => function($data) {
                        $url = Yii::app()->createUrl('users/customerDocs?id=' . $data->id);
                        return $url;
                    },
                    'visible'=>'$data->role_change_to_flag!=0'
               ),
            )
        ),
    ),
));
}else{
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
                'htmlOptions' => array('style' => 'width: 2%')
            ),
            array(
                'name'=>'first_name',
                'header'=>'Full Name',
                'value'=>array($model,'FullName'),
                'htmlOptions' => array('style' => 'width: 6%'),
            ),
            array(
                'name'=>'email',
                'htmlOptions' => array('style' => 'width: 4%')
            ),
            array(
                'name'=>'phone',
                'htmlOptions' => array('style' => 'width: 5%'),
            ),
            array(
                'header' => 'Role change request',
                'name'=>'role_change_to',
                'type'=>'raw',
                'value'=> array($model,'RoleChangeRequest'),
                'htmlOptions' => array('style' => 'width: 8%'),
            ),
            array(
                'name'=>'status',
                'type'=>'raw',
                'value'=> function ($data){
                if($data->status==2)
                    return "<a class='btn btn-success btn-xs' href='" . Yii::app()->createAbsoluteUrl('Users/banned', array('id' => $data->id)) . "'>Active</a>";
                else if($data->status==1)
                    return "<a class='btn btn-warning btn-xs' href='" . Yii::app()->createAbsoluteUrl('Users/activate', array('id' => $data->id)) . "'>Inactive</a>";
                else
                    return "<a class='btn btn-danger btn-xs' href='" . Yii::app()->createAbsoluteUrl('Users/deactivate', array('id' => $data->id)) . "'>Banned</a>";
                },
                'htmlOptions' => array('style' => 'width: 4%')
                ),
                array(
                    'header' => 'Account Status',
                    'name'=>'account_status',
                    'type'=>'raw',
                    'value'=> array($model,'CheckAccountStatus'),
                    'htmlOptions' => array('style' => 'width: 8%')
                ),
                array(
                    'name' => 'created',
                    'value' => array($model,'userJoinedDate'),
                    // 'value'=>'Yii::app()->dateFormatter->format("m/d/y",$data->created)',
                    'htmlOptions' => array('style' => 'width: 8%')
                ),
                array(
                    'header' => 'Action',
                    'class' => 'ButtonColumn',
                    'template' => '{update}{delete}{view}{docs}',
                    'htmlOptions' => array('style' => 'width: 10%','class' => "button-column"),
                    'buttons' => array(
                        'update' => array(
                            'label' => '<i class="icon-pencil icon-white"></i> Edit', // text label of the button
                            'options' => array('class' => "btn btn-primary btn-xs", 'title' => 'Update','style'=>'margin-right: 0px'),
                            'url' => function($data) {
                                $url = Yii::app()->createUrl('users/update/' . $data->id);
                                return $url;
                              },
                              'visible'=>'$data->user_address->address!=""',
                            ),
                            'delete' => array(//the name {reply} must be same
                                'label' => '<i class="icon-remove icon-white"></i> Delete', // text label of the button
                                'options' => array('class' => "btn btn-danger btn-xs delete", 'title' => 'Delete'),
                                'url' => function($data) {
                                    $url = Yii::app()->createUrl('users/customerDelete/' . $data->id);
                                    return $url;
                                },
                                'visible'=>'$data->user_address->address!=""',
                             ),
                             'view' => array( //the name {reply} must be same
                                  'label' => '<i class="icon-remove icon-white"></i> View', // text label of the button
                                 'options' => array('class'=>"btn btn-info btn-xs ",'title'=>'View','style'=>'margin-right: 4px'),
                                  'url' => function($data) {
                                      $url = Yii::app()->createUrl('users/customerView?id=' . $data->id);
                                      return $url;
                                    },
                                    'visible'=>'$data->user_address->address!=""',
                                    
                             ),
                             'docs' => array( //the name {reply} must be same
                                  'label' => '<i class="icon-remove icon-white"></i> Docs', // text label of the button
                                 'options' => array('class'=>"btn btn-success btn-xs ",'title'=>'Docs','style'=>'margin-left: 0px'),
                                  'url' => function($data) {
                                      $url = Yii::app()->createUrl('users/customerDocs?id=' . $data->id);
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
/*(function () {
    var previous;
    $(".account_act_drop").on('focus', function () {
        id = this.id;
        previous = this.value;
    }).change(function() {
    	var result = window.confirm('Are you sure wants to change the status?');
            if (result == true) {
        	var user_id = $(this).attr('ref');
        	var value = $(this).val();
        	$.ajax({
        		type:'POST',
        		dataType:'html',
        		data:{'user_id':user_id,'value':value},
        		url:'<?php echo Yii::app()->createAbsoluteUrl("users/UpdateAccountStatus"); ?>',
        		success:function(response){
            		if(response=="3"){
						alert("Unable to approve this user now, admin couldn't find the documents for this user");
                    }else{
        				window.location.reload();
                	}
        		},error: function(jqXHR, textStatus, errorThrown) {
        			window.location.reload();
                }
        	});
        }else{
        	$("#"+id).val(previous);
        }
    });
})();*/
    $(document).on('change', '.account_act_drop', function() {
        id = this.id;
        previous = this.value;
        var result = window.confirm('Are you sure wants to change the status?');
            if (result == true) {
            var user_id = $(this).attr('ref');
            var value = $(this).val();
            $.ajax({
                type:'POST',
                dataType:'html',
                data:{'user_id':user_id,'value':value},
                url:'<?php echo Yii::app()->createAbsoluteUrl("users/UpdateAccountStatus"); ?>',
                success:function(response){
                    if(response=="3"){
                        alert("Unable to approve this user now, admin couldn't find the documents for this user");
                    }else{
                        window.location.reload();
                    }
                },error: function(jqXHR, textStatus, errorThrown) {
                    window.location.reload();
                }
            });
        }else{
            $("#"+id).val(previous);
        }
    });

window.setInterval(Getinterval, 5000);
function Getinterval(tim){
	var saved_time  = $('#time_frame').val();
    var dNow = new Date();
    new_mu_date = dNow.getMonth()+1;
    var localdate= dNow.getFullYear() + '-' + new_mu_date + '-' + dNow.getDate() + ' ' + dNow.getHours() + ':' + dNow.getMinutes() + ':' + dNow.getSeconds();
	var seconds = datediff(saved_time, localdate, 'seconds') % 60;
	var minutes = datediff(saved_time, localdate, 'minutes') % 60;
	var hours = datediff(saved_time, localdate, 'hours');
	//alert(hours);
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








