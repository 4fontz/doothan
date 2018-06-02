<?php
$this->breadcrumbs=array(
	'Requestor'=>array('customer'),
	'create',
);

$this->menu=array(
	array('label'=>'list Users','url'=>array('index')),
	array('label'=>'manage Users','url'=>array('admin')),
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

<h1>Create Requestor</h1>
<br>
<hr>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
</div>
</div>
