<?php
$this->breadcrumbs = array(
    'payment view management',
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

<h1>payment details</h1>

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
    'dataProvider' => $model->search(),
    'summaryText' => "{start} - {end} of {count}",
    'ajaxUpdate' => false,
    'enableDragDropSorting'=>false,
   //'orderField' => 'id',
    'idField' => 'id',
    'filter' => $model,
    'htmlOptions' => array('class' => 'span12 table-responsive'),
     'itemsCssClass' => 'table',
    'columns' => array(
   // 'id',
   // 'title',
   // 'card_token',
        // array(
        //     'header' => 'id',
        //     'value' => array($model, 'view_title'),
        //     'htmlOptions' => array('style' => 'width: 6%')
            
        // ),

        array(
            'name' => 'title',
            'header'=>'title',
             'htmlOptions' => array('style' => 'width: 12%')
        ),

        
       

        array(
            'name' => 'card_token',
            'value' => array($model, 'card_token_view'),
            'htmlOptions' => array('style' => 'width: 6%')
            
        ),

        array(
            'name' => 'is_default',
            'header'=>'default',
            'value' => array($model, 'default_status'),
             'htmlOptions' => array('style' => 'width: 12%')
        ),

        array(
            'name' => 'status',
            'value' => array($model, 'payment_status'),
             'htmlOptions' => array('style' => 'width: 12%'),
             'filter' => CHtml::activeDropDownList($model, 'status', CHtml::ListData(Helper::paymentStatus(), 'id', 'name'), array('prompt' => 'select all')),
        ),

         

       array(
            'name' => 'created_at',
            'header'=>'date/time',
            'value' => array($model, 'PaymentTime'),
             'htmlOptions' => array('style' => 'width: 10%'),
             'filter'=>''
        ),



        // array(
        //     'name' => 'updated_at',
        //     'header'=>'date updated',
        //     'value'=>'Yii::app()->dateFormatter->format("d/m/y",$data->updated_at)',
        //     'htmlOptions' => array('style' => 'width: 10%')    
        // ),
       
        //array(
            //'class' => 'bootstrap.widgets.TbButtonColumn',
            //'template'=>'{view}'
        //),
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
