<?php
$this->breadcrumbs = array(
    'customer booking management',
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
<h1>booking list</h1>
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
<?php //echo CHtml::link('<i class="fa fa-plus" aria-hidden="true"></i> add New',' ', array('class' => 'btn btn-success pull-right btn-sm view-btn')); ?>
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
 //print_r($model);
$this->widget('ext.yiisortablemodel.widgets.SortableCGridView', array(
    'id' => 'booking-grid',
    'dataProvider' => $model->search(),
    'summaryText' => "{start} - {end} of {count}",
    'ajaxUpdate' => false,
    'enableDragDropSorting'=>false,
    'orderField' => 'id',
    'idField' => 'id',
    'filter' => $model,
    'htmlOptions' => array('class' => 'span12 table-responsive'),
     'itemsCssClass' => 'table',
    'columns' => array(
      array(
      'header' => 'id',
      'name'=>'id',
      'htmlOptions' => array('style' => 'width: 6%'),
       ),
      array(
      'header' => 'date/time',
      'value' => array($model, 'getAppointmentDate'),
      'htmlOptions' => array('style' => 'width: 7%'),
       'filter'=>''
       ),
       array(
            'name' => 'id',
            'header'=>'therapist',
            //'value'=>'$data->id',
            'value' => array($model, 'therapistName'),
             'htmlOptions' => array('style' => 'width: 10%'),
             'filter'=>''
        ),
       array(
            'name' => 'id',
            'header'=>'customer',
            'value' => array($model, 'customerName'),
             'htmlOptions' => array('style' => 'width: 10%;text-align:center'),
             'filter' => CHtml::activeTextField($model,'user_id',array()),
        ),
       array(
            'name' => 'id',
            'header'=>'address',
            'value' => array($model, 'userAddress'),
            'htmlOptions' => array('style' => 'width: 10%'),
            'filter'=>''
        ),
       array(
            'name' => 'massage_duration',
            'header'=>'duration',
             'htmlOptions' => array('style' => 'width: 6%; text-align:center'),
             'filter'=>''
        ),
       array(
            'header' => 'package',
            'value' => array($model, 'getPackages'),
            'htmlOptions' => array('style' => 'width: 12%'),
             'filter'=>''
      ),
       array(
            'header' => 'gender preference',
            'name' => 'therapist_gender',
            'htmlOptions' => array('style' => 'width: 10%;text-align:center'),
            'filter' => CHtml::activeDropDownList($model, 'therapist_gender', CHtml::ListData(Helper::therapistGender(), 'id', 'name'), array('prompt' => 'select')),
       ),

       array(
            'header' => 'massage type',
            'value' => array($model, 'getMassageType'),
            'htmlOptions' => array('style' => 'width: 10%;text-align:center'),
             'filter' => CHtml::activeTextField($model,'massage_type',array()),
      ),

       array(
            'name' => 'id',
            'header'=>'status',
            'value' => array($model, 'status'),
             'htmlOptions' => array('style' => 'width: 10%;text-align:center'),
              'filter' => CHtml::activeDropDownList($model, 'status', CHtml::ListData(Helper::bookingStatus(), 'id', 'name'), array('prompt' => 'select all')),
        ),
       array(
                'header' => 'Action',
                'class' => 'ButtonColumn',
                'template' => '{view}',
                'buttons' => array(
                  'view' => array( 
                    'label' => '<i class="icon-remove icon-white"></i> View', // text label of the button
                    'options' => array('class'=>"btn btn-info btn-xs ",'title'=>''),
                    'url' => function($data) {
                       $url = Yii::app()->createUrl('users/bookingsView/' . $data->id);
                       return $url;
                    }
                  ),

                )
        ),

       array(
          'value' => array($model,'bookingCancelLink'),
        )

   
    ),
));
?>
 <div class="modal fade" id="therapist-details-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">therapist list</h4>
      </div>
      <div class="modal-body">
        <div id="tlist"></div>
        <input type="hidden" id='bookId' name="bookId"/>
        </div>
     
    </div>
  </div>
</div> 
<script type="text/javascript">
    jQuery(function ($) {
        jQuery(document).on("change", '#pageSize', function () {
            $.fn.yiiGridView.update('users-grid', {data: {pageSize: $(this).val()}});
        });
       
    });
</script> 

<script type="text/javascript">
    jQuery(function ($) {
        jQuery(document).on("change", '#pageSize', function () {
            $.fn.yiiGridView.update('booking-grid', {data: {pageSize: $(this).val()}});
        });
       
    });
</script>
<script>
$(document).ready(function () {

    $('body').on('click', '.therapist-link', function (e) {

            var url = $(this).attr('href');
            var booking_id = $(this).data('book_id');
            $.ajax({
                type: "POST",
                url: url,
                data: {booking_id: booking_id},
            })
                    .done(function (data) {
                        $('#therapist-details-modal').modal();
                        $('#therapist-details-modal #tlist').html(data);
                        e.preventDefault();
                    });
            e.preventDefault();
        });

});
</script>
<div>
</div>