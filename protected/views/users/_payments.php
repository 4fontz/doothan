<div class="row">
	<div class="col-md-12">
		<div class="form-actions">
    		<a class="btn btn-primary pull-right btn-sm view-btn" style="margin-bottom: 10px;" href="javascript:void(0);" data-toggle="modal" data-target="#myModal" id="open_model"> Pay Now</a>            				
    	</div>
	</div>
</div>

<div class="clear"></div>
<div class="space_10px"></div>
<div class="clear"></div>
<div class="" id="custom_flash"></div>
<?php
if ($fee_model->search()) {
    $this->widget('ext.yiisortablemodel.widgets.SortableCGridView', array(
        'id' => 'payment-grid',
        'dataProvider' => $fee_model->search(),
        'summaryText' => "{start} - {end} of {count}",
        'ajaxUpdate' => true,
        'enableDragDropSorting'=>false,
        'idField' => 'id',
        'filter' => $fee_model,
        'htmlOptions' => array('class' => 'span12 table-responsive'),
         'itemsCssClass' => 'table',
        'columns' => array(
            array(
                'name'=>'id',
                'header' => 'Id',
                'type'=>'raw',
                'htmlOptions' => array('style' => 'width: 7%')
            ),
            array(
                'name'=>'request_id',
                'header' => 'Request',
                'value' => array($fee_model,'RequestData'),
                'htmlOptions' => array('style' => 'width: 10%')
            ),
            array(
                'header' => 'Amount',
                'name'=>'amount',
                'htmlOptions' => array('style' => 'width: 10%'),
            ),
            array(
                'header' => 'Mode',
                'name'=>'mode',
                'value' => array($fee_model,'Mode'),
                'htmlOptions' => array('style' => 'width: 10%'),
            ),
            array(
                'header' => 'Description',
                'name'=>'description',
                'htmlOptions' => array('style' => 'width: 10%'),
            ),
            array(
                'name' => 'created_at',
                'header'=>'Paid on',
                'value' => array($fee_model,'userJoinedDate'),
                'htmlOptions' => array('style' => 'width: 10%')
            ),
        ),
    ));
}
?>
<script type="text/javascript">
$('#open_model').on('click',function(){
id = '<?php echo $basic_model->id?>';
type="0";
    $.ajax({
    	type:'POST',
    	dataType:'html',
    	data:{'user_id':id},
    	url:'<?php echo Yii::app()->createAbsoluteUrl("Users/Payments"); ?>',
    	success:function(response){
    		$('.modal-content').html(response);
    	},error: function(jqXHR, textStatus, errorThrown) {
    		//window.location.reload();
        }
    });
});
</script>