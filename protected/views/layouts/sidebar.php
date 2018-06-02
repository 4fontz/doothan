<?php 
$requestCount = Request::model()->countByAttributes(array('status'=>'Request Placed'));
$doothanCount = Users::model()->countByAttributes(array('account_status'=>'CALL_VERIFICATION_PENDING','member_type'=>'doothan'));
$dropboxCount = Users::model()->countByAttributes(array('account_status'=>'CALL_VERIFICATION_PENDING','member_type'=>'dropbox'));
$opencallbacks = Feedback::model()->countByAttributes(array('type'=>'1','status'=>'N'));
?>
<aside class="main-sidebar">
<!-- sidebar: style can be found in sidebar.less -->
<section class="sidebar">
  <!-- Sidebar user panel -->
  <div class="user-panel">
    <div class="pull-left image">
      <img src="<?php echo Yii::app()->request->baseUrl; ?>/vendor/dist/img/user2-160x160.png" class="img-circle" alt="User Image">
    </div>
    <div class="pull-left info">
      <p>Rajeevan Valappil</p>
      <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
    </div>
  </div>
 
  <!-- sidebar menu: : style can be found in sidebar.less -->
  <ul class="sidebar-menu" data-widget="tree">
    <li class="header">MAIN NAVIGATION</li>
    <li><a href="<?php echo Yii::app()->baseUrl . '/dashboard' ?>"><i class="fa fa-tachometer"></i> <span>Dashboard</span></a></li>
    <li><a href="<?php echo Yii::app()->baseUrl.'/users/index?type=requester'; ?>"><i class="fa fa-users"></i> <span>Requestors</span></a></li>
    <li>
    	<a href="<?php echo Yii::app()->baseUrl.'/users/index?type=doothan'; ?>">
    		<i class="fa fa-user"></i> <span>Doothans</span>
    		<span class="pull-right-container">
              <small class="label pull-right bg-red"><?php echo ($doothanCount>0)?$doothanCount:'';?></small>
          	</span>
    	</a>
    </li>
    <li>
    	<a href="<?php echo Yii::app()->baseUrl.'/users/index?type=dropbox'; ?>">
    		<i class="fa fa-archive"></i> <span>Dropbox Owners</span>
    		<span class="pull-right-container">
              <small class="label pull-right bg-red"><?php echo ($dropboxCount>0)?$dropboxCount:'';?></small>
          	</span>
    	</a>
    </li>
    <li>
    	<a href="<?php echo Yii::app()->baseUrl .'/request/index'; ?>">
    	  <i class="fa fa-shopping-cart"></i> <span>Requests</span>
    	  <span class="pull-right-container">
              <small class="label pull-right bg-blue"><?php echo ($requestCount>0)?$requestCount:'';?></small>
          </span>
        </a>
    </li>
    <li><a href="<?php echo Yii::app()->baseUrl .'/request/payments'; ?>"><i class="fa fa-money"></i> <span>Payments</span></a></li>
    <li><a href="<?php echo Yii::app()->baseUrl .'/feedback/index'; ?>"><i class="fa fa-comments-o" aria-hidden="true"></i> <span>Feedbacks</span></a></li>
    <li>
    	<a href="<?php echo Yii::app()->baseUrl .'/feedback/callback'; ?>">
    		<i class="fa fa-phone" aria-hidden="true"></i> <span>Callbacks</span>
    		<span class="pull-right-container">
              <small class="label pull-right bg-purple"><?php echo ($opencallbacks>0)?$opencallbacks:'';?></small>
          	</span>
    	</a>
    </li>
    <li><a href="<?php echo Yii::app()->request->baseUrl; ?>/activity"><i class="fa fa-circle-o text-yellow"></i> <span>Activity Logs</span></a></li> 
    <li><a href="<?php echo Yii::app()->request->baseUrl; ?>/settings/update/1"><i class="fa fa-cogs" aria-hidden="true"></i> <span>Settings</span></a></li> 
    <li><a href="<?php echo Yii::app()->request->baseUrl; ?>/notifications"><i class="fa fa-bell" aria-hidden="true"></i> <span>Alerts</span></a></li>
    <li><a href="<?php echo Yii::app()->request->baseUrl; ?>/notifications/custom_notifications"><i class="fa fa-bell-o" aria-hidden="true"></i> <span>Custom Notifications</span></a></li> 
  </ul>
</section>
<!-- /.sidebar -->
</aside>