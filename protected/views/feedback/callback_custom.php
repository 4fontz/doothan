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
            'htmlOptions' => array('style' => 'width: 6%'),
        ),
        array(
            'name'=>'phone_number',
            'header'=> 'Phone Number',
            'type' => 'raw',
            'value'=> array($model,'PhoneNumber'),
            'htmlOptions' => array('style' => 'width: 6%'),
        ),
        array(
            'name'=>'status',
            'type'=>'raw',
            'value'=> function ($data){
            if($data->status=='Y')
                return "<a class='btn btn-success btn-xs' id='sm_".$data->id."' href='" . Yii::app()->createAbsoluteUrl('feedback/statusChange', array('id' => $data->id)) . "'>Closed</a>";
            else if($data->status=='N')
                return "<a class='btn btn-warning btn-xs' id='sm_".$data->id."' href='" . Yii::app()->createAbsoluteUrl('feedback/statusChange', array('id' => $data->id)) . "'>Open</a>";
            },
            'htmlOptions' => array('style' => 'width: 4%')
            ),
        array(
            'name' => 'created_at',
            'value' => array($model,'userJoinedDate'),
            // 'value'=>'Yii::app()->dateFormatter->format("m/d/y",$data->created)',
            'htmlOptions' => array('style' => 'width: 8%')
        ),
        array(
            'name'=>'comments',
            'header'=> 'Comment',
            'type' => 'raw',
            'value'=> array($model,'UpdateComment'),
            'htmlOptions' => array('style' => 'width: 6%'),
        ),
    ),
));
?>