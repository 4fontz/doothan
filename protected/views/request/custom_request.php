<?php

if ($model->search()) {
    $condition_text = '$data->status!="Payment completed"';
    $condtion_value = '$data->status=="Request Placed" && $data->doothan_id==0';
    $visible_cond = '$data->user->first_name!="" && $data->doothan_id!=0';
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
                'htmlOptions' => array('style' => 'width: 6%')
            ),
            array(
                'name'=>'request_code',
                'header' => 'Request Code',
                'value'=>array($model,'Order_Code'),
                'type'=>'raw',
                'htmlOptions' => array('style' => 'width: 8%')
            ),
            array(
                'header' => 'Requestor Name',
                'htmlOptions' => array('style' => 'width: 10%'),
                'value'=>array($model,'Order_Name'),
                'type'=>'raw',
                //'value' => '!empty($data->user->first_name)?$data->user->first_name:"Not Available"',
                'filter' => CHtml::activeTextField($model, 'first_name'),
            ),
            array(
                'name'=>'item_details',
                'header' => 'Item Details',
                'value'=>array($model,'ItemDetailsText'),
                'htmlOptions' => array('style' => 'width: 8%')
            ),
            array(
                'header' => 'Address',
                'value' => array($model,'requestorAddress'),
                'htmlOptions' => array('style' => 'width: 12%'),
            ),
            array(
                'name'=>'phone',
                'header' => 'Phone',
                'value'=>array($model,'Order_Phone'),
                'type'=>'raw',
                'htmlOptions' => array('style' => 'width: 8%'),
                
            ),
            array(
                'header' => 'Dropbox Owner',
                'htmlOptions' => array('style' => 'width: 10%'),
                'value' => array($model,'dropBoxOwner'),
                'type'=>'raw',
                'filter' => CHtml::activeTextField($model, 'dropbox_owner'),
            ),
            array(
                //'name' => 'cancel_request',
                'header'=>'Doothan',
                'value' => array($model,'DoothanFind'),
                'type'=>'raw',
                'filter' => CHtml::activeTextField($model, 'doothan_name'),
                // 'value'=>'Yii::app()->dateFormatter->format("d/m/y h:i",$data->created_on)',
                'htmlOptions' => array('style' => 'width: 10%')
            ),
            array(
                'header' => 'Status',
                // 'value' => array($model,'dropBoxOwner'),
                'name' => 'status',
                'htmlOptions' => array('style' => 'width: 10%'),
                'value' => array($model,'StatusText'),
                'type'=>'raw',
                'filter' => CHtml::activeDropDownList($model, 'status', CHtml::ListData(Helper::requestStatus(), 'id', 'name'), array('prompt' => 'select')),
            ),
            
            array(
                'name' => 'created_on',
                'header'=>'Created on',
                'value' => array($model,'userJoinedDate'),
                // 'value'=>'Yii::app()->dateFormatter->format("d/m/y h:i",$data->created_on)',
                'htmlOptions' => array('style' => 'width: 10%')
            ),
            /*array(
             'name' => 'cancel_request',
             'header'=>'Cancel Request',
             'value' => array($model,'CancelRequest'),
             'type'=>'raw',
             // 'value'=>'Yii::app()->dateFormatter->format("d/m/y h:i",$data->created_on)',
             'htmlOptions' => array('style' => 'width: 10%')
             ),*/
            array(
                'header' => 'Action',
                'class' => 'ButtonColumn',
                'template' => '{view}{fare}',
                'htmlOptions' => array('style' => 'width: 15%','class' => "button-column"),
                'buttons' => array(
                    
                    'view' => array( //the name {reply} must be same
                        'label' => '<i class="icon-remove icon-white"></i> View', // text label of the button
                        'options' => array('class'=>"btn btn-info btn-xs ",'title'=>'View','style'=>'margin-right:0px',),
                        'url' => function($data) {
                        $url = Yii::app()->createUrl('request/requestview/' . $data->id);
                        return $url;
                        }
                        ),
                        /*'delete' => array(//the name {reply} must be same
                         'label' => '<i class="icon-remove icon-white"></i> Delete', // text label of the button
                         'options' => array('class' => "btn btn-danger btn-xs delete", 'title' => 'Delete','style'=>'margin-right:0px;margin-left: 3px;'),
                         'url' => function($data) {
                         $url = Yii::app()->createUrl('request/RequestDeleted/' . $data->id);
                         return $url;
                         },
                         'visible'=>$condition_text,
                         ),*/
                        'fare' => array( //the name {reply} must be same
                            'label' => '<i class="icon-remove icon-white"></i> Fare', // text label of the button
                            'options' => array('class'=>"btn btn-success btn-xs ",'title'=>'Fare','style'=>'margin-left:3px'),
                            'url' => function($data) {
                            $url = Yii::app()->createUrl('request/servicecharge/' . $data->id);
                            return $url;
                            },
                            'visible'=>$visible_cond,
                            ),
                            )
                ),
                ),
                ));
    
}
?>