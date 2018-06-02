<?php
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
  $('.search-form').toggle();
  return false;
});
$('.search-form form').submit(function(){
  $.fn.yiiGridView.update('feedback-grid', {
    data: $(this).serialize()
  });
  return false;
});
");
?>
<div id="myModalContent" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Feedback Text</h4>
      </div>
      <div class="modal-body">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
<div class="box" style="border-top: 3px solid #fff;">
    <div class="box-body">
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
            'htmlOptions' => array('style' => 'width: 5%')
        ),
        array(
            'name'=>'user_id',
            'header'=> 'User',
            'type' => 'html',
            'value'=> array($model,'FullName'),
            'htmlOptions' => array('style' => 'width: 15%'),
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
            'value'=> array($model,'ViewMoreContent'),
            'htmlOptions' => array('style' => 'width: 10%'),
        ),
    ),
));
?>


<script type="text/javascript">
    jQuery(function ($) {
        jQuery(document).on("change", '#pageSize', function () {
            $.fn.yiiGridView.update('users-grid', {data: {pageSize: $(this).val()}});
        });
       
    });


function show_more_content(param){
	text = $(param).attr('id');
	$('.modal-body').html('<p>'+text+'</p>');
}    
</script> 
</div>
</div>