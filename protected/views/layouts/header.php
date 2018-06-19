<header class="main-header">
    <!-- Logo -->
    <a href="<?php echo Yii::app()->baseUrl."/administrator"?>" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><img src="<?php echo Yii::app()->request->baseUrl; ?>/vendor/dist/img/logo01.png" alt="logo"></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><img src="<?php echo Yii::app()->request->baseUrl; ?>/vendor/dist/img/logo.png" alt="logo"></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
		<?php echo $loggedUserDetails = Admin::model()->findByPk(Yii::$app->user->id);?>
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- Messages: style can be found in dropdown.less-->
          
          <!-- Notifications: style can be found in dropdown.less -->
          
          <!-- Tasks: style can be found in dropdown.less -->
          
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <?php if(Yii::app()->user->getId()==1){?>
              	<img src="<?php echo Yii::app()->request->baseUrl; ?>/vendor/dist/img/user2-160x160.png" class="user-image" alt="User Image">
              <?php }else{?>
              	<img src="<?php echo Yii::app()->request->baseUrl; ?>/vendor/dist/img/user2-160x1601.JPG" class="user-image" alt="User Image">
              <?php }?>
              <span class="hidden-xs"><?php echo $loggedUserDetails->first_name." ".$loggedUserDetails->last_name?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
              <?php if(Yii::app()->user->getId()==1){?>
                <img src="<?php echo Yii::app()->request->baseUrl; ?>/vendor/dist/img/user2-160x160.png" class="img-circle" alt="User Image">
			<?php }else{?>
				<img src="<?php echo Yii::app()->request->baseUrl; ?>/vendor/dist/img/user2-160x1601.JPG" class="img-circle" alt="User Image">
			<?php }?>
                <p>
                  <?php echo $loggedUserDetails->first_name." ".$loggedUserDetails->last_name?>
                  <!-- <small>Member since Nov. 2012</small> -->
                </p>
              </li>
             
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                 <!--  <a href="#" class="btn btn-default btn-flat">Profile</a> -->
                </div>
                <div class="pull-right">
                  <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/administrator/logout" class="btn btn-default btn-flat">Sign out</a>
                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->
         
        </ul>
      </div>
    </nav>
  </header>