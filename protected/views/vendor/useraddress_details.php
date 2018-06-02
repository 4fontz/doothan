<?php

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
  $('.search-form').toggle();
  return false;
});
$('.search-form form').submit(function(){
  $.fn.yiiGridView.update('customers-grid', {
    data: $(this).serialize()
  });
  return false;
});
");
?>

<?php if ($addressData) {  ?>
 



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

            <div class="box-header with-border">
              <h3 class="box-title"></h3>
            </div>
            
            
                <?php
//print_r($userAddressModel->getUserAddressDetails());
$this->widget('ext.yiisortablemodel.widgets.SortableCGridView', array(
    'id' => 'customers-grid',
    'dataProvider' => $userAddressModel->getUserAddressDetails(),
    'summaryText' => "",
    'ajaxUpdate' => false,
    'enableDragDropSorting'=>false,
   //'orderField' => 'id',
    'idField' => 'id',
    'filter' => '',
    'htmlOptions' => array('class' => 'span12 table-responsive'),
     'itemsCssClass' => 'table',
    'columns' => array(
       array(  
         'value' => array($userAddressModel,'addressView'),
       ),
       array(  
         'value' => array($userAddressModel,'editButton'),
       ),
       
      array(
                'header' => 'Action',
                'class' => 'ButtonColumn',
                'template' => '{delete}',
                //'htmlOptions' => array('style' => 'width: 10%'),
                'buttons' => array(
                    
                    'delete' => array(//the name {reply} must be same
                        'label' => '<i class="fa fa-trash-o" aria-hidden="true"></i>', // text label of the button
                        'options' => array('class' => "btn btn-danger btn-xs delete", 'title' => ''),
                        'url' => function($data) {
                       $url = Yii::app()->createUrl('users/DeleteUserAddress/' . $data->id);
                            return $url;
                        }
                    ),
                    
                   
                )
            ),
    ),
));
}
else
  {
    
  }
?>   

 

     

<script type="text/javascript">
    jQuery(function ($) {
      //alert($("#customers-grid").html());
      $('.filters').html('');
      $('#customers-grid_c0').parents('tr').html('');
        jQuery(document).on("change", '#pageSize', function () {
            $.fn.yiiGridView.update('customers-grid', {data: {pageSize: $(this).val()}});
        });
    });

</script> 

<script>
$(document).ready(function () {

    $('body').on('click', '#address-link', function (e) {
            var url = $(this).attr('href');
            var address_id = $(this).data('address_id');
          //  window.location.href  = url+'?id='+address_id;
             $.ajax({
                 type: "GET",
                 url: url,
                 data: {address_id: address_id},
             })

            // .done(function (data) {
            //             $('#address-details-modals').modal();
            //           //  $('#therapist-details-modal #tlist').html(data);
            //             e.preventDefault();
            // });
                    
           // e.preventDefault();
        });

});
</script>
