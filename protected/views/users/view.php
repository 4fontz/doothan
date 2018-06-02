<?php
$this->breadcrumbs = array(
     ucfirst($basic_model->member_type).'s'=>array('index','type'=>$basic_model->member_type),
    'View user', 
);
?>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.2.5/jquery.fancybox.css">
<script src="//code.jquery.com/jquery-3.2.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.2.5/jquery.fancybox.min.js"></script>
<section class="content">
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      
    </div>
  </div>
</div>
<div class="row">
	<div class="col-md-12">
	<?php echo CHtml::link('<i class="fa fa-angle-double-left" aria-hidden="true"></i> back', $this->createUrl('users/index?type='.$basic_model->member_type), array('class' => 'btn btn-primary pull-right btn-sm view-btn','style'=>'margin-bottom: 10px;')); ?>
	</div>
</div>
      <div class="row">
        <div class="col-md-3">
         <?php
                  $image    = Yii::app()->params['profileImageBucketUrl'].$basic_model->image;
                  $fbImage  = $basic_model->facebook_image;
                  $noImage  = Yii::app()->request->baseUrl.'/images/no-image.jpg';
                  $users_id = $basic_model->id;
         ?>
          <!-- Profile Image -->
          <div class="box box-primary">
            <div class="box-body box-profile">
              <?php if (!empty($basic_model->image)) { ?>
                  <a href="<?php echo $image; ?>" data-fancybox data-caption="Profile">
                  	<img class="profile-user-img img-responsive img-circle" src="<?php echo $image; ?>" alt="User profile picture" style="border-radius: 0!important;">
                  </a>	
              <?php } else if (!empty($fbImage)) { ?>
                  <a href="<?php echo $fbImage; ?>" data-fancybox data-caption="Profile">
                  	<img class="profile-user-img img-responsive img-circle" src="<?php echo $fbImage; ?>" alt="User profile picture">
                  </a>	
              <?php } else { ?>
              	<img class="profile-user-img img-responsive img-circle" src="<?php  echo $noImage; ?>" alt="User profile picture">
              <?php } ?>
              <h3 class="profile-username text-center"><?php echo strtolower($basic_model->first_name); ?></h3>
              <p class="text-muted text-center" ><?php echo "(".$basic_model->gender.")";?></p>
              <?php
                $member_type  = $basic_model->member_type;
                if ($member_type == 'requester') {
                   $member_type = "Requester";
                } else if ($member_type == "dropbox") {
                   $member_type = "Dropbox";
                } else {
                   $member_type = "Doothan"; 
                }
              ?>
              <p class="text-muted text-center" ><b><?php echo $member_type; ?></b></p>

              <ul class="list-group list-group-unbordered">
                <li class="list-group-item">
                  <b>Total Requests</b> <a class="pull-right"><?php echo $totalCounts; ?></a>
                </li>
                <li class="list-group-item">
                  Request Placed <a class="pull-right"><?php echo Helper::order_status_by_user('Request Placed',$basic_model->id); ?></a>
                </li>
                <li class="list-group-item">
                  Waiting for payment <a class="pull-right"><?php echo Helper::order_status_by_user('Waiting for payment',$basic_model->id); ?></a>
                </li>
                <li class="list-group-item">
                  Payment in progress <a class="pull-right"><?php echo Helper::order_status_by_user('Payment in progress',$basic_model->id); ?></a>
                </li>
                <li class="list-group-item">
                  Payment completed <a class="pull-right"><?php echo Helper::order_status_by_user('Payment completed',$basic_model->id); ?></a>
                </li>
                <li class="list-group-item">
                  Delivered to dropbox <a class="pull-right"><?php echo Helper::order_status_by_user('Delivered to dropbox',$basic_model->id); ?></a>
                </li>
                <li class="list-group-item">
                  Received to dropbox <a class="pull-right"><?php echo Helper::order_status_by_user('Received to dropbox',$basic_model->id); ?></a>
                </li>
                <li class="list-group-item">
                  Delivered <a class="pull-right"><?php echo Helper::order_status_by_user('Delivered',$basic_model->id); ?></a>
                </li>
                <li class="list-group-item">
                  Cancelled <a class="pull-right"><?php echo Helper::order_status_by_user('Cancelled',$basic_model->id); ?></a>
                </li>
              </ul>
            </div>
          </div>
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Contact Details</h3>
            </div>
            <div class="box-body">
              <strong><i class="fa fa-book margin-r-5"></i> Address</strong>
              <p class="text-muted">
                <?php 
                $country = explode('+91',$addressEdtModel->country);
                $address  = $addressEdtModel->address.' '.$addressEdtModel->city. ' '.$addressEdtModel->state . ' '.$country[0].' '.$addressEdtModel->postal_code;
                $address  = wordwrap($address, 30, "<br />\n",true);
                echo $address;
                ?>
              </p>
              <hr>
              <strong><i class="fa fa-map-marker margin-r-5"></i> Location</strong>
              <p class="text-muted"><?php echo $addressEdtModel->city.' '.$addressEdtModel->postal_code; ?></p>
            </div>
          </div>
        </div>
        <div class="col-md-9">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#basic_info" data-toggle="tab">Basic Informations</a></li>
              <li><a href="#activites" data-toggle="tab">Bookings Details</a></li>
              <li><a href="#activity_logs" data-toggle="tab">Activity Log</a></li>
              <li><a href="#feedback" data-toggle="tab">Feedback</a></li>
              <?php if ($basic_model->member_type != 'requester') {?>
              	<li><a href="#payments" data-toggle="tab">Payments</a></li>
              <?php }?>
            </ul>
            <div class="tab-content">
            	<div class="active tab-pane" id="basic_info">
              		<?php echo $this->renderPartial('more_details', array('basic_model'=>$basic_model,'addressEdtModel'=>$addressEdtModel)); ?>
              	</div>
                <div class="tab-pane" id="activites">
                	<?php echo $this->renderPartial('bookings_details', array('bookingmodel'=>$bookingmodel)); ?>
              	</div>
              	<div class="tab-pane" id="activity_logs">
                	<?php echo $this->renderPartial('activity_log',array('model'=>$activity_model,'pages'=>$pages,'activity_count'=>$activity_count));?>
              	</div>
              	<div class="tab-pane" id="feedback">
                	<?php echo $this->renderPartial('feedback_list',array('model'=>$feedback_data));?>
              	</div>
              	<?php if ($basic_model->member_type != 'requester') {?>
                  	<div class="tab-pane" id="payments">
                    	<?php echo $this->renderPartial('_payments',array('fee_model'=>$fee_model,'basic_model'=>$basic_model)); ?>
                  	</div>
              	<?php }?>
             </div>
          </div>
        </div>
      </div>
    </section>
    <script type="text/javascript">
    jQuery(function ($) {
        jQuery(document).on("change", '#pageSize', function () {
            $.fn.yiiGridView.update('customers-grid', {data: {pageSize: $(this).val()}});
        });
    });
</script> 