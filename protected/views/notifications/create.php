<?php
$this->breadcrumbs=array(
    'Custom Notification'=>array('custom'),
    'Create',
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
<br>
<hr>

<?php echo $this->renderPartial('create_custom', array('model'=>$model)); ?>
</div>
</div>