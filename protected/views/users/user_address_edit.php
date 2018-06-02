<?php
$this->breadcrumbs=array(
	'users'=>array('customer'),
	$model->id=>array('view','id'=>$model->id),
	'update address',
);

$this->menu=array(
	array('label'=>'List Users','url'=>array('index')),
	array('label'=>'Create Users','url'=>array('create')),
	array('label'=>'View Users','url'=>array('view','id'=>$model->id)),
	array('label'=>'Manage Users','url'=>array('admin')),
);
?>
<div class="box">
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

<h1>update user address : <?php //echo $model->first_name; ?></h1>
<?php echo CHtml::link('<i class="fa fa-angle-double-left" aria-hidden="true"></i> back', $this->createUrl("users/$addressEdtModel->user_id"), array('class' => 'btn btn-primary pull-right btn-sm view-btn')); ?>
<br>
<hr>
<?php 
//$addressId  = $addressEdtModel->id;
//$addressModel = UserAddress::model()->findByPk($addressId);
?>
<?php echo $this->renderPartial('_form_add_address',array('model'=>$addressEdtModel)); ?>
</div>
</div>