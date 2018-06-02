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
            'value' => array($model,'UserData'),
            'htmlOptions' => array('style' => 'width: 10%'),
        ),
        array(
            'name'=>'device_type',
            'header'=> 'device_type',
            'htmlOptions' => array('style' => 'width: 15%'),
        ),
        array(
            'name'=>'message',
            'value' => array($model,'MessageContent'),
            'htmlOptions' => array('style' => 'width: 15%'),
        ),
        array(
            'name' => 'queue_status',
            'header'=> 'Status',
            // 'value'=>'Yii::app()->dateFormatter->format("m/d/y",$data->created)',
            'htmlOptions' => array('style' => 'width: 15%')
        ),
        array(
            'name' => 'error_log',
            'header'=> 'Error Log',
            'value' => array($model,'ErrorLog'),
            //'value' => array($model,'ErorLog'),
            // 'value'=>'Yii::app()->dateFormatter->format("m/d/y",$data->created)',
            'htmlOptions' => array('style' => 'width: 15%')
        ),
        array(
            'name' => 'started_on',
            'value' => array($model,'StartedOn'),
            // 'value'=>'Yii::app()->dateFormatter->format("m/d/y",$data->created)',
            'htmlOptions' => array('style' => 'width: 15%')
        ),
    ),
));
?>