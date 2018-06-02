<div class="box-body">
  		<?php
        $this->widget('ext.yiisortablemodel.widgets.SortableCGridView', array(
            'id' => 'users-grid',
            'dataProvider' => $User_model->public_search(),
            'summaryText' => "{start} - {end} of {count}",
            'ajaxUpdate' => true,
            'enableDragDropSorting'=>false,
            //'orderField' => 'id',
            'idField' => 'id',
            'filter' => $User_model,
            'htmlOptions' => array('class' => 'span12 table-responsive'),
            'itemsCssClass' => 'table',
            'pager' => array(
                'header' => '',
                'prevPageLabel' => 'Previous  <',
                'nextPageLabel' => 'Next  >',
                'firstPageLabel'=>'First',
                'lastPageLabel'=>'Last  >>'
            ),
            'pagerCssClass' => 'pagination pull-right',
            'columns' => array(
                array(
                    'name'=>'id',
                    'header' => 'Id',
                    'htmlOptions' => array('style' => 'width: 4%')
                ),
                array(
                    'name'=>'first_name',
                    'htmlOptions' => array('style' => 'width: 6%'),
                ),
                array(
                    'name'=>'last_name',
                    'htmlOptions' => array('style' => 'width: 6%')
                ),
                array(
                    'name'=>'email',
                    'htmlOptions' => array('style' => 'width: 5%')
                ),
                array(
                    'name'=>'member_type',
                    'htmlOptions' => array('style' => 'width: 5%'),
                ),
                
                array(
                    'name' => 'created',
                    'value' => array($User_model,'userJoinedDate'),
                    // 'value'=>'Yii::app()->dateFormatter->format("m/d/y",$data->created)',
                    'htmlOptions' => array('style' => 'width: 8%')
                ),
                array(
                    'name'=>'aadhar',
                    'filter'=>false,
                    'type'=>'raw',
                    'htmlOptions' => array('style' => 'width: 5%'),
                    'value' => array($User_model,'AdharShow'),
                ),
                
                array(
                    'name' => 'photo_id',
                    'type'=>'raw',
                    'filter'=>false,
                    'value' => array($User_model,'PhotoShow'),
                    'htmlOptions' => array('style' => 'width: 8%')
                ),
            ),
        ));
        ?>
</div>
<style type="text/css">

ul.yiiPager .selected a{
background-color:#337ab7;
}
</style>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.2.5/jquery.fancybox.css">
<script src="//code.jquery.com/jquery-3.2.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.2.5/jquery.fancybox.min.js"></script>
<script type="text/javascript">
$("[data-fancybox]").fancybox({
});
</script>