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
    'dataProvider' => $bookingmodel->search(),
    'summaryText' => "{start} - {end} of {count}",
    'ajaxUpdate' => false,
    'enableDragDropSorting'=>false,
    'orderField' => 'id',
    'idField' => 'id',
    'filter' => $bookingmodel,
    'htmlOptions' => array('class' => 'span12 table-responsive'),
     'itemsCssClass' => 'table',
    'columns' => array(
       array(
            'header'=>'booking id',
            'name' =>'id',
             'htmlOptions' => array('style' => 'width: 10%'),
             'filter'=>''
        ),
       array(
            'name' => 'booked_on',
            'header'=>'booked on',
            'value' => array($bookingmodel, 'bookingTime'),
             
             'filter'=>''
        ),
       array(
            'name' => 'additional_notes',
            'header'=>'booking notes',
            'htmlOptions' => array('style' => 'width: 18%; text-align:left'),
            'value' => array($bookingmodel, 'bookingNotes'),
             
             'filter'=>''
        ),
      
       
       array(
            'name' => 'appointment_on',
            'value' => array($bookingmodel, 'appointTime'),
             'filter'=>''
        ),
       array(
            'name' => 'id',
            'header'=>'therapist',
            'value' => array($bookingmodel, 'therapistName'),
             'filter'=>''
        ),
       
        array(
            'name' => 'status',
            'header'=>'status',
            'value' => array($bookingmodel, 'status'),
             'htmlOptions' => array('style' => 'width: 6%'),
            'filter' => '',
             //'filter' => CHtml::activeDropDownList($bookingmodel, 'status', CHtml::ListData(Helper::bookingStatus(), 'id', 'name'), array('prompt' => 'Select all')),
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
