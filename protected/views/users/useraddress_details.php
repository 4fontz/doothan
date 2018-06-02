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

<?php if ($addressData) {?>
<hr>
<?php
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
    ),
));
}else{
    
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
