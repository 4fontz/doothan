<?php
$this->breadcrumbs = array(
    'Error '.$error['code'],
);?>
<div class="box">
    <div class="box-body" style="text-align: center;">
		<img src="<?php echo Yii::app()->request->baseUrl; ?>/images/Page-not-found.png">
		<div class="col-sm-12" style="padding:50px;background-color: #D0D0D0;margin-top: 30px;font-size: 18px;font-weight: bold;">
			<?php echo $error['message'];?>
		</div>
	</div>
</div>