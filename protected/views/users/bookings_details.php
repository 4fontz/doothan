<?php

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
                    <!-- <?= $pageSizeDropDown; ?><label>results per page</label> -->
                </div>
                
            </div>
            <?php Yii::app()->clientScript->registerCss('initPageSizeCSS', '.page-size-wrap{width: 10%; float: left;}'); ?>
            
        </div>
         <div class="clear"></div>
        <div class="space_10px"></div>
        <div class="clear"></div>

<?php

if ($bookingmodel->search()) {
    $this->widget('ext.yiisortablemodel.widgets.SortableCGridView', array(
        'id' => 'customers-grid',
        'dataProvider' => $bookingmodel->search(),
        'summaryText' => "{start} - {end} of {count}",
        'ajaxUpdate' => true,
        'enableDragDropSorting'=>false,
        //'orderField' => 'id',
        'idField' => 'id',
        'filter' => $bookingmodel,
        'htmlOptions' => array('class' => 'span12 table-responsive'),
         'itemsCssClass' => 'table',
        'columns' => array(
            array(
                'name'=>'request_code',
                'header' => 'Request Code',
                'value'=>array($bookingmodel,'Order_Code'),
                'type'=>'raw',
                'htmlOptions' => array('style' => 'width: 7%')
            ),
            array(
                'name'=>'item_details',
                'header' => 'Item Details',
                'value'=>array($bookingmodel,'ItemDetailsText'),
                'htmlOptions' => array('style' => 'width: 10%')
            ),
            array(
                'header' => 'Address',
                'value' => array($bookingmodel,'requestorAddress'),
                'htmlOptions' => array('style' => 'width: 10%'),
            ),
            array(
                'name'=>'phone',
                'header' => 'Phone',
                'value'=>array($bookingmodel,'Order_Phone'),
                'type'=>'raw',
                'htmlOptions' => array('style' => 'width: 10%')
            ),
            
            array(
                'header' => 'Dropbox Owner',
                'htmlOptions' => array('style' => 'width: 8%'),
                'value' => array($bookingmodel,'dropBoxOwner'),
                'type'=>'raw',
                'filter' => CHtml::activeTextField($bookingmodel, 'dropbox_id'),
            ),
            array(
                'header' => 'Status',
                // 'value' => array($bookingmodel,'dropBoxOwner'),
                'name' => 'status',
                'htmlOptions' => array('style' => 'width: 7%'),
                'value' => array($bookingmodel,'StatusText'),
                'type'=>'raw',
                'filter'=>false,
                //'filter' => CHtml::activeDropDownList($bookingmodel, 'status', CHtml::ListData(Helper::requestStatus(), 'id', 'name'), array('prompt' => 'select')),
            ),
            
            array(
                'name' => 'created_on',
                'header'=>'Created on',
                'value' => array($bookingmodel,'userJoinedDate'),
                'htmlOptions' => array('style' => 'width: 10%')
            ),
        ),
    ));
}
?>
<script type="text/javascript">
    jQuery(function ($) {
        jQuery(document).on("change", '#pageSize', function () {
            $.fn.yiiGridView.update('customers-grid', {data: {pageSize: $(this).val()}});
        });
    });

</script> 