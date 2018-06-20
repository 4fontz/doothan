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
                <div id="forgot_body"  style="display:none;">
       				<p class="login-box-msg">Forgot Password</p>
                    <?php
                    $form = $this->beginWidget('CActiveForm', array(
                        'id' => 'forgot-form',
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
                        <?php echo $form->textField($model, 'username_forgot', array('class'=>'form-control','placeholder' => 'username')); ?>
                        <!--<input type="email" name="user_email" id="user_email" class="form-control" placeholder="Email or Username">-->
                        <span class="glyphicon glyphicon-user form-control-feedback"></span>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                        	<span id="span_msg"></span>
                            <button name="forgotform" id="forgot_pass_btn" type="submit" class="btn btn-block btn-flat loginbtn">Submit</button>
                            <a href="#" class="" style="float:right;padding:10px;" id="back_to_login">Back to Login</a>
                        </div>
                    </div>         
            		<?php $this->endWidget(); ?>
                </div>
                <div id="login_body">
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
                        <?php echo $form->textField($model, 'username', array('class'=>'form-control','placeholder' => 'Email')); ?>
                        <?php echo $form->error($model,'username',array('style'=>'color:#FF0000'));?>
                        <!--<input type="email" name="user_email" id="user_email" class="form-control" placeholder="Email or Username">-->
                        <span class="glyphicon glyphicon-user form-control-feedback"></span>
                    </div>
                    <div class="form-group has-feedback">
                        <?php echo $form->passwordField($model, 'password', array('class'=>'form-control','placeholder' => 'enter password')); ?>
                        <?php echo $form->error($model,'password',array('style'=>'color:#FF0000')); ?>
                        <!--<input type="password" name="user_password" id="user_password" class="form-control" placeholder="Password">-->
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <?php echo CHtml::tag('button', array(
						        'name'=>'loginform',
						        'class'=>'btn btn-block btn-flat loginbtn',
						        'type'=>'submit'
						      ), '<i class="ace-icon fa fa-key"></i><span class="bigger-110"> log in</span>'); ?>
                            <a href="javascript:void(0);" class="" style="float:right;padding:10px;" id="forgot_password">Forgot Password</a>
                        </div>
                    </div>
                    <?php $this->endWidget(); ?>
                </div>
			</div>
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
<script type="text/javascript">
$('#forgot_password').on('click',function(){
	$('#login_body').hide();
	$('#forgot_body').show();
});

$('#back_to_login').on('click',function(){
	$('#forgot_body').hide();
	$('#login_body').show();
});

$('form#forgot-form').submit(function(event){
	$('#forgot_pass_btn').html('loading...').css({'cursor':'not-allowed'});
    event.preventDefault();
    $.ajax({
        url:'<?php echo Yii::app()->request->baseUrl;?>/administrator/forgot',
        type:'POST',
        dataType:'json',
        data:$('form#forgot-form').serialize(),
        success:function(data){
        	$('#forgot_pass_btn').html('Submit').css({'cursor':'pointer'});alert(data.status);
            if(data.status=="false"){
				$('#span_msg').html(data.message).css({'color':'red'});
				$('#AdminForm_username_forgot').css({'border':'1px solid red'});
            }else{
            	$('#AdminForm_username_forgot').css({'border':'1px solid #d2d6de'});
            	$('#span_msg').html(data.message).css({'color':'green'});
            }
        }
    })
});
</script>
</body>
</html>





