

<?php
$this->breadcrumbs = array(
    'Vendor management',
);

$this->menu = array(
    array('label' => 'List Requestors', 'url' => array('index')),
    array('label' => 'Create Requestors', 'url' => array('create')),
);

Yii::app()->clientScript->registerScript('search', "

$('.search-button').click(function(){

  $('.search-form').toggle();
  return false;
});
$('.search-form form').submit(function(){
  alert('Heyyy');
  $.fn.yiiGridView.update('users-grid', {
    data: $(this).serialize()
  });
  return false;
});
");

?>
<div class="box">
    <div class="box-body">
<h1>Vendor list</h1>
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
<?php echo CHtml::link('<i class="fa fa-plus" aria-hidden="true"></i> add new', $this->createUrl('vendor/create'), array('class' => 'btn btn-success pull-right btn-sm view-btn')); ?>
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
    'id' => 'users-grid',
     'dataProvider' => $model->search(),
    'summaryText' => "{start} - {end} of {count}",
     'ajaxUpdate' => false,
     'enableDragDropSorting'=>false,
   'filter' => $model,
   'orderField' => 'id',
    'idField' => 'id',
    
    //'htmlOptions' => array('class' => 'span12 table-responsive'),
     'itemsCssClass' => 'table',

    'columns' => array(
      array(
        'name'=>'id',
        'htmlOptions' => array('style' => 'width: 10%')
      ),
      // array(
      //   'name'=>'username',
      //   'htmlOptions' => array('style' => 'width: 10%')
      // ),
//    'password',
        
        array(
          'header' => 'Name',
          'name'=>'first_name',
          'htmlOptions' => array('style' => 'width: 10%')
        ),
        
        array(
          'header' => 'Email',
          'name'=>'email',
          'htmlOptions' => array('style' => 'width: 10%')
          ),
        array(
          'header' => 'Phone',
          'name'=>'phone',
          'htmlOptions' => array('style' => 'width: 10%')
        ),
        array(
          'header' => 'Status',
          'value'=>array($model,'status'),
          'filter' => CHtml::activeDropDownList($model, 'status', CHtml::ListData(Helper::status(), 'id', 'name'), array('prompt' => 'Select all')),
          'htmlOptions' => array('style' => 'width: 10%')
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
        // array(
        //     'name' => 'status',
        //     'value' => array($model, 'status'),
        //     'filter' => CHtml::activeDropDownList($model, 'status', CHtml::ListData(Helper::status(), 'id', 'name'), array('prompt' => 'Select all')),
        // ),
        /* 'verification_code',
          'passwordreset_code',
          'invite_code',
          'invited_by', */
//        'member_type',
//    'created',
//    'updated',
        // array(
        //     'class' => 'bootstrap.widgets.TbButtonColumn',
        //     'template'=>'{view}'
        // ),

          array(
                'header' => 'Action',
                'class' => 'ButtonColumn',
                'template' => '{update}{delete}',
                'htmlOptions' => array('style' => 'width: 10%','class' => "button-column"),
                //'options' => array('class' => "button-column"),
                'buttons' => array(
                    'update' => array(
                        'label' => '<i class="icon-pencil icon-white"></i> Edit', // text label of the button
                        'options' => array('class' => "btn btn-primary btn-xs", 'title' => ''),
                        'url' => function($data) {
                        $url = Yii::app()->createUrl('vendor/update/' . $data->id);
                         return $url;
                        }
                    ),
                    'delete' => array(//the name {reply} must be same
                        'label' => '<i class="icon-remove icon-white"></i> Delete', // text label of the button
                        'options' => array('class' => "btn btn-danger btn-xs delete", 'title' => ''),
                        'url' => function($data) {
                       $url = Yii::app()->createUrl('vendor/customerDelete/' . $data->id);
                            return $url;
                        }
                    ),
                //     'view' => array( //the name {reply} must be same
                //     'label' => '<i class="icon-remove icon-white"></i> View', // text label of the button
                //     'options' => array('class'=>"btn btn-info btn-xs ",'title'=>''),                    
                // ),
                   
                )
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

</script> 
</div>
</div>








