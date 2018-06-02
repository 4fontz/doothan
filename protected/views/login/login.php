<!DOCTYPE html>
<html>
    <head>
        <title>Admin Login</title>
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
                <p class="login-box-msg">please log in</p>
                <?php
                $form = $this->beginWidget('CActiveForm', array(
                    'id' => 'login-form',
                    'enableClientValidation' => true,
                    'clientOptions' => array(
                        'validateOnSubmit' => true
                    ),
                    'htmlOptions' => array(
                        'class' => 'separate-sections'
                    )
                ));
                ?>
                <?php if (Yii::app()->user->hasFlash('error')): ?>
                        <div class="alert alert-error">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <strong>Oh Snap!</strong> <?php echo Yii::app()->user->getFlash('error'); ?>
                        </div>
                    <?php endif; ?> 
            <div  class="form-group has-feedback">
                <?php echo $form->textField($model, 'username', array('class'=>'form-control','placeholder' => 'username')); ?>
                <!--<input type="email" name="user_email" id="user_email" class="form-control" placeholder="Email or Username">-->
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <?php echo $form->passwordField($model, 'password', array('class'=>'form-control','placeholder' => 'enter password')); ?>
                <!--<input type="password" name="user_password" id="user_password" class="form-control" placeholder="Password">-->
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>

            <div class="row">

                <div class="col-xs-12">
                    <button name="loginform" type="submit" class="btn btn-block btn-flat loginbtn">log in</button>
                    <!--<input type="submit" class="btn btn-primary btn-block btn-flat" value="Sign In"/>-->
                </div>
            </div>
            </div>

            <?php $this->endWidget(); ?>


        </div>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jstz.min.js">
</script>
<?php
  function showclienttime()
  {
    if(!isset($_COOKIE['Timezone']))
    {
?>
 <script type="text/javascript">
 var Cookies = {};
 Cookies.create = function (name, value, days) {
 if (days) {
 var date = new Date();
 date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
 var expires = "; expires=" + date.toGMTString();
 } else {
 var expires = "";
 }
    var tz = jstz.determine(); // Determines the time zone of the browser client
    var timezone = tz.name(); //'Asia/Kolhata' for Indian Time.

 document.cookie = name + "=" + timezone + expires + "; path=/";
 this[name] = timezone;
 }
 var now = new Date();
 Cookies.create("Timezone",now.getTimezoneOffset(),1);
 </script>
 <?php
 } else {

 }
 }
showclienttime();
?>
        <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/vendor/jQuery-2.1.4.min.js"></script>
        <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/vendor/bootstrap.min.js"></script>
        <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/vendor/icheck.min.js"></script>

    </body>
</html>





