<?php
$this->breadcrumbs = array(
    'Create'
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

<h1>Write feedback</h1>
<?php echo CHtml::link('<i class="fa fa-angle-double-left" aria-hidden="true"></i> back', $this->createUrl('feedback/index'), array('class' => 'btn btn-primary pull-right btn-sm view-btn')); ?>
<br>
<hr>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
</div>
</div>
