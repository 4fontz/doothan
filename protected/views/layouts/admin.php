<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>ദൂതൻ  | Dashboard</title>
  <link rel="icon" type="image/png" href="<?php echo Yii::app()->request->baseUrl; ?>/images/favicon.ico"> 
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/vendor/bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/vendor/bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/vendor/bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/vendor/dist/css/AdminLTE.css">
  <!-- Custom style sheet -->
  <link href="<?php echo Yii::app()->request->baseUrl; ?>/css/admin/custom.css" rel="stylesheet" media="screen">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/vendor/dist/css/skins/_all-skins.min.css">
  <!-- Morris chart -->
  <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/vendor/bower_components/morris.js/morris.css">
  <!-- jvectormap -->
  <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/vendor/bower_components/jvectormap/jquery-jvectormap.css">
  <!-- Date Picker 
  <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/vendor/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">-->
  <!-- Daterange picker 
  <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/vendor/bower_components/bootstrap-daterangepicker/daterangepicker.css">-->
  <!-- bootstrap wysihtml5 - text editor -->
  <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/vendor/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  <!-- map style -->
  <!-- <link type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/vendor/dist/iCheck/jquery-jvectormap.css" rel="stylesheet" /> -->
  <!-- <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/vendor/dist/iCheck/all.css"> -->
</head>
<script type="text/javascript">
var Baseurl = '<?php echo Yii::app()->request->baseUrl; ?>';
</script>
<body class="hold-transition skin-blue sidebar-mini">
    <?php echo $this->renderPartial('//layouts/header', array()); 
            if (!$this->hideSidebar) {
                echo $this->renderPartial('//layouts/sidebar', array());
            }
            ?>
<!-- <div id="preloader">
    <img src="<?php echo Yii::app()->request->baseUrl; ?>/vendor/dist/img/logo01.png" alt="">
    <div style="color:green;font-weight:bold;">Doothan</div>
</div>
<style>
#preloader
{
	margin:0px auto;
	padding:0px;
	width:100%;
	height:100%;
	text-align:center;
    background:#ffffff;
    background: -moz-linear-gradient(-45deg,  #ffffff 0%, #ffffff 100%);
    background: -webkit-gradient(linear, left top, right bottom, color-stop(0%,#ffffff), color-stop(100%,#ffffff));
    background: -webkit-linear-gradient(-45deg,  #ffffff 0%,#ffffff 100%);
    background: -o-linear-gradient(-45deg,  #ffffff 0%,#ffffff 100%);
    background: -ms-linear-gradient(-45deg,  #ffffff 0%,#ffffff 100%);
    background: linear-gradient(135deg,  #ffffff 0%,#ffffff 100%);
	background-attachment: fixed;
	position:fixed;
	font: 400 14px 'Open Sans', Arial, sans-serif;
	color:#fff;
	z-index:99999;

	
}
#preloader img
{
	margin:0px auto;
	padding: 20% 0 0;
}
</style> -->
<script type="text/javascript">
/*$(window).load(function(){
	$('#preloader').fadeOut('slow',function(){$(this).remove();});
});*/
</script>
 <div class="wrapper">
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
     <section class="content-header">
        <h1>
            <?php echo isset($this->page_title) ? $this->page_title : ''; ?> 
        </h1>  
        <div class="clear"></div>                                                                                                                                                 
        <?php if (isset($this->breadcrumbs)): ?>
            <?php
            $this->widget('bootstrap.widgets.TbBreadcrumbs', array('links' => $this->breadcrumbs,
                'homeLink' => CHtml::link('<i class="fa fa-dashboard"></i>Home', Yii::app()->createAbsoluteUrl('Dashboard')),
            ));                        
            ?>

            <!-- breadcrumbs -->
    <?php endif ?>
    </section>

  <?php echo $content; ?>
    <!-- /.content -->
  </div>

<?php echo $this->renderPartial('//layouts/footer', array()); ?>
 </body>
 <div class="se-pre-con"></div>
 <style>
 .no-js #loader { display: none;  }
.js #loader { display: block; position: absolute; left: 100px; top: 0; }
.se-pre-con {
	position: fixed;
	left: 0px;
	top: 0px;
	width: 100%;
	height: 100%;
	z-index: 9999;
	background: url('<?php echo Yii::app()->request->baseUrl; ?>/images/loader.gif') center no-repeat #fff;
}
 </style>
 <script type="text/javascript">
 $(window).load(function() {
		$(".se-pre-con").fadeOut("slow");
 });
 </script>
</html>
