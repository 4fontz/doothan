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
            'value'=> array($model,'ViewMore'),
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