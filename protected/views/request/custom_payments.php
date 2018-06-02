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