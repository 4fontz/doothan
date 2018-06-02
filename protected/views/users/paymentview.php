<?php 
$this->breadcrumbs = array(
    'payment  management',
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

<h1>manage payment statistics</h1>
<?php //echo CHtml::link('<i class="fa fa-plus" aria-hidden="true"></i> add new',' ', array('class' => 'btn btn-success pull-right btn-sm view-btn')); ?>
<?php //echo CHtml::link('<i class="fa fa-plus" aria-hidden="true"></i> add New', $this->createUrl('users/create'), array('class' => 'btn btn-success pull-right btn-sm view-btn')); ?>
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
            'htmlOptions' => array('style' => 'width: 15%')
            
        ),
        'first_name',
        'email',

         array(
            //'name' => 'id',
            'header' => 'Manage',
            'value' => array($model, 'manageview'),
            'htmlOptions' => array('style' => 'width: 15%')
            
        ),

//         array(
//                 'header' => 'payment management ',
//                 'value' => array($model, 'manageview'),
// //             'htmlOptions' => array('style' => 'width: 10%')
//                     ),
       
        /*
          'phone',
          'city',
          'state',
          'country',
          'zip',
          'image',
          'facebook_image', */
//        'status',

        
        
         // array(
         //        'header' => 'Action',
         //        'class' => 'ButtonColumn',
         //        'template' => '{update}{delete}',
         //        //'htmlOptions' => array('style' => 'width: 10%'),
         //        'buttons' => array(
         //            'update' => array(
         //                'label' => '<i class="icon-pencil icon-white"></i> Edit', // text label of the button
         //                'options' => array('class' => "btn btn-primary btn-xs", 'title' => ''),
         //                'url' => function($data) {
         //                $url = Yii::app()->createUrl('users/update/' . $data->id);
         //                 //return $url;
         //                }
         //            ),
         //            'delete' => array(//the name {reply} must be same
         //                'label' => '<i class="icon-remove icon-white"></i> Delete', // text label of the button
         //                'options' => array('class' => "btn btn-danger btn-xs delete", 'title' => ''),
         //                'url' => function($data) {
         //                $url = Yii::app()->createUrl('users/delete/' . $data->id);
         //                   // return $url;
         //                }
         //            ),
                   
         //        )
         //    ),
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
