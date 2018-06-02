<?php
$this->breadcrumbs = array(
    'customer management',
);

$this->menu = array(
    array('label' => 'List Users', 'url' => array('index')),
    array('label' => 'Create Users', 'url' => array('create')),
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
<h1>customer list</h1>
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
<?php echo CHtml::link('<i class="fa fa-plus" aria-hidden="true"></i> add new', $this->createUrl('users/create'), array('class' => 'btn btn-success pull-right btn-sm view-btn')); ?>
<br/>
<hr>
 <?php
        $pageSize = Yii::app()->user->getState('pageSize', Yii::app()->params['defaultPageSize']);
        $pageSizeDropDown = CHtml::dropDownList(
                        'pageSize', $pageSize, array(10 => 10, 15 => 15, 30 => 30, -1 => 'All'), array(
                    'id' => 'pageSize'
                        )
        );
?>
      <div class="table-toolbar">
            <div class="page-size-wrap" style="width:100%">
                <div class="results-perpage">
                    <?= $pageSizeDropDown; ?><label>results per page</label>
                </div>
                
            </div>
            <?php Yii::app()->clientScript->registerCss('initPageSizeCSS', '.page-size-wrap{width: 10%; float: left;}'); ?>
            
        </div>
         <div class="clear"></div>
        <div class="space_10px"></div>
        <div class="clear"></div>
<?php

$this->widget('ext.yiisortablemodel.widgets.SortableCGridView', array(
    'id' => 'customers-grid',
    'dataProvider' => $model->customer_search(),
    'summaryText' => "{start} - {end} of {count}",
    'ajaxUpdate' => false,
    'enableDragDropSorting'=>false,
   //'orderField' => 'id',
    'idField' => 'id',
    'filter' => $model,
    'htmlOptions' => array('class' => 'span12 table-responsive'),
     'itemsCssClass' => 'table',
    'columns' => array(
//    'id',
        array(
            'name' => 'id',
            'value' => array($model, 'customerId'),
            'htmlOptions' => array('style' => 'width: 6%')
            
        ),
        array(
            'name' => 'first_name',
            'header'=>'first name',
             'htmlOptions' => array('style' => 'width: 10%;text-align:center')
        ),
        
        // array(
        //     'name' => 'last_name',
        //     'header'=>'last name',
        //      'htmlOptions' => array('style' => 'width: 10%')
        // ),

//    'password',
       // 'first_name',
       // 'last_name',
         array(
            'name' => 'email',
            'header'=>'email',
             'htmlOptions' => array('style' => 'width: 10%;')
        ),
        //'email',
         array(
            'name' => 'gender',
            'header'=>'gender',
            'htmlOptions' => array('style' => 'width: 10%;text-align:center'),
            'filter' => CHtml::activeDropDownList($model, 'gender', CHtml::ListData(Helper::userGender(), 'id', 'name'), array('prompt' => 'select')),
        ),
        array(
            'name' => 'phone',
            'header'=>'phone',
             'htmlOptions' => array('style' => 'width: 6%')
        ),
        /*
          'phone',
          'city',
          'state',
          'country',
          'zip',
          'image',
          'facebook_image', */
//        'status',

        array(
            'name' => 'booking',
            'header'=>'total bookings',
            'value' => array($model, 'CustomerTotalBooking'),
            'htmlOptions' => array('style' => 'width: 5%;text-align:center'),
            'filter'=>''

        ),
        array(
            'name' => 'total sales',
            'header'=>'total sales',
            'value' => array($model, 'CustomerTotalSales'),
            'htmlOptions' => array('style' => 'width: 5%;text-align:center'),
            'filter'=>''  
        ),
        array(
            'name' => 'rating',
            'header'=>'rating',
            'value' => array($model, 'CustomerTotalRating'),
            'htmlOptions' => array('style' => 'width: 5%;text-align:center'),
            'filter'=>''  
        ),
        array(
            'name' => 'status',
            'value' => array($model, 'status'),
            'htmlOptions' => array('style' => 'width: 11%;text-align:center'),
            'filter' => CHtml::activeDropDownList($model, 'status', CHtml::ListData(Helper::status(), 'id', 'name'), array('prompt' => 'select all')),
        ),
        array(
            'name' => 'created',
            'header'=>'date joined',
            'value' => array($model,'userJoinedDate'),
           // 'value'=>'Yii::app()->dateFormatter->format("m/d/y",$data->created)',
            'htmlOptions' => array('style' => 'width: 10%')    
        ),
       
        //array(
            //'class' => 'bootstrap.widgets.TbButtonColumn',
            //'template'=>'{view}'
        //),
         array(
                'header' => 'Action',
                'class' => 'ButtonColumn',
                'template' => '{update}{delete}{view}',
                //'htmlOptions' => array('style' => 'width: 10%'),
                'buttons' => array(
                    'update' => array(
                        'label' => '<i class="icon-pencil icon-white"></i> Edit', // text label of the button
                        'options' => array('class' => "btn btn-primary btn-xs", 'title' => ''),
                        'url' => function($data) {
                        $url = Yii::app()->createUrl('users/update/' . $data->id);
                         return $url;
                        }
                    ),
                    'delete' => array(//the name {reply} must be same
                        'label' => '<i class="icon-remove icon-white"></i> Delete', // text label of the button
                        'options' => array('class' => "btn btn-danger btn-xs delete", 'title' => ''),
                        'url' => function($data) {
                       $url = Yii::app()->createUrl('users/customerDelete/' . $data->id);
                            return $url;
                        }
                    ),
                    'view' => array( //the name {reply} must be same
                    'label' => '<i class="icon-remove icon-white"></i> View', // text label of the button
                    'options' => array('class'=>"btn btn-info btn-xs ",'title'=>''),
                    
                ),
                   
                )
            ),
        
    ),
));
?>
<script type="text/javascript">
    jQuery(function ($) {
        jQuery(document).on("change", '#pageSize', function () {
            $.fn.yiiGridView.update('customers-grid', {data: {pageSize: $(this).val()}});
        });
       
    });

</script> 
</div>
</div>