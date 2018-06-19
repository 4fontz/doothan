<!DOCTYPE html>
<html>
    <head>
        <title>Password Reset</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">   
      <link rel="icon" type="image/png" href="<?php echo Yii::app()->request->baseUrl; ?>/images/favicon.ico"> 
        <!-- Bootstrap -->
        <link href="<?php echo Yii::app()->request->baseUrl; ?>/css/admin/bootstrap.min.css" rel="stylesheet" media="screen">
        <link href="<?php echo Yii::app()->request->baseUrl; ?>/css/admin/font-awesome.min.css" rel="stylesheet" media="screen">
        <link href="<?php echo Yii::app()->request->baseUrl; ?>/css/admin/ionicons.min.css" rel="stylesheet" media="screen">
        <link href="<?php echo Yii::app()->request->baseUrl; ?>/vendor/dist/css/AdminLTE.css" rel="stylesheet" media="screen">
       
        <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
    </head>
    <body class="hold-transition login-page" id="login">

        <div class="login-box">
            <div class="login-logo">
                <a><img src="<?php echo Yii::app()->request->baseUrl; ?>/vendor/dist/img/logo.png"></a>
            </div><!-- /.login-logo -->
            <div class="login-box-body">
                <div id="forgot_body">
       				<p class="login-box-msg">Forgot Password</p>
                    <?php
                    $form = $this->beginWidget('CActiveForm', array(
                        'id' => 'reset-form',
                        'enableClientValidation' => true,
                        'clientOptions' => array(
                            'validateOnSubmit' => true
                        ),
                        'htmlOptions' => array(
                            'class' => 'separate-sections'
                        )
                    ));
                    ?>
                    <?php if (Yii::app()->user->hasFlash('success')): ?>
                    <div class="alert alert-success">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <?php echo Yii::app()->user->getFlash('success'); ?>
                    </div>
                    <?php endif; ?>
                    <?php if (Yii::app()->user->hasFlash('error')): ?>
                    <div class="alert alert-error">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <strong>Oh Snap!</strong> <?php echo Yii::app()->user->getFlash('error'); ?>
                    </div>
                    <?php endif; ?> 
                    <div  class="form-group has-feedback">
                        <?php echo $form->PasswordField($model,'password',array('class'=>'form-control','placeholder'=>"New Password")); ?>
						<?php echo $form->error($model,'password',array('style'=>'color:#FF0000'));?>
                        <span class="glyphicon glyphicon-user form-control-feedback"></span>
                    </div>
                    <div  class="form-group has-feedback">
                        <?php echo $form->passwordField($model,'verifyPassword',array('class'=>'form-control','placeholder'=>"Confirm Password")); ?>
						<?php echo $form->error($model,'verifyPassword',array('style'=>'color:#FF0000')); ?>
                        <span class="glyphicon glyphicon-user form-control-feedback"></span>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                        	<span id="span_msg"></span>
                            <?php echo CHtml::tag('button', array(
						        'name'=>'reset_password',
						        'class'=>'btn btn-block btn-flat loginbtn',
						        'type'=>'submit'
						      ), '<i class="ace-icon fa fa-key"></i><span class="bigger-110"> Update</span>'); ?>
                            <a href="#" class="" style="float:right;padding:10px;" id="back_to_login">Back to Login</a>
                        </div>
                    </div>         
            		<?php $this->endWidget(); ?>
                </div>
			</div>
        </div>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/vendor/jQuery-2.1.4.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/vendor/bootstrap.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/vendor/icheck.min.js"></script>
</body>
</html>