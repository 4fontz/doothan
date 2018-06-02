
<section class="content">
      <div class="row">
        <div class="col-md-3">
         <?php
                  $image    = Yii::app()->params['profileImageBucketUrl'].$model->image;
                  $fbImage  = $model->facebook_image;
                  $noImage  = Yii::app()->params['profileImageBucketUrl'].'86ad2b0b-7acc-5122-8701-4770c6526e1f.jpg';    
                  $users_id = $model->id;
         ?>
          <!-- Profile Image -->
          <div class="box box-primary">
            <div class="box-body box-profile">

              <?php if (!empty($model->image)) { ?>
              <img class="profile-user-img img-responsive img-circle" src="<?php echo $image; ?>" alt="User profile picture">
              <?php } else if (!empty($fbImage)) { ?>
              <img class="profile-user-img img-responsive img-circle" src="<?php echo $fbImage; ?>" alt="User profile picture">
              <?php } else { ?>
              <img class="profile-user-img img-responsive img-circle" src="<?php  echo $noImage; ?>" alt="User profile picture">
              <?php } ?>
              <h3 class="profile-username text-center"><?php echo strtolower($model->first_name); ?></h3>
              <?php
                $member_type  = $model->member_type;
                if ($member_type == 0) {
                   $member_type = "user";
                } else if ($member_type == 1) {
                   $member_type = "therapist";
                } else {
                   $member_type = "admin"; 
                }
              ?>
              <p class="text-muted text-center" ><b><?php echo $member_type; ?></b></p>

              <p class="text-muted text-center" ><b>credits:&nbsp;<?php echo $total_credits;?>&nbsp;hour(s)</b></p>

              <ul class="list-group list-group-unbordered">
                <li class="list-group-item">
                  <b>total bookings</b> <a class="pull-right"><?php echo $totalCounts; ?></a>
                </li>
                <li class="list-group-item">
                  <b>completed bookings</b> <a class="pull-right"><?php echo $usrCompletedBkngs ?></a>
                </li>
                <li class="list-group-item">
                  <b>cancelled bookings</b> <a class="pull-right"><?php echo $cancelledBkngs ?></a>
                </li>
                <li class="list-group-item">
                  <b>therapist is on the way</b> <a class="pull-right"><?php echo $therapistOnway ?></a>
                </li>
              </ul>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

          <!-- About Me Box -->

          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">details</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <strong><i class="fa fa-book margin-r-5"></i> address  </strong> 
                
                <a href="AddUserAddress/<?php echo $users_id ?>" class="btn btn-primary btn-xs plusbtn">add</a> 
              
              <?php //echo CHtml::link('<i class="fa fa-plus-circle" aria-hidden="true"></i> add', $this->createUrl('usraddressadd/'.$users_id), array('class' => 'btn btn-primary btn-xs plusbtn')); ?>
              <?php 
                
                echo $this->renderPartial('useraddress_details', array('userAddressModel'=>$userAddressModel,'addressData'=>$addressDetails));

               /* foreach ($addressData as $addressDetails) {
                    $room   = $addressDetails['room'];
                    $block  = $addressDetails['block'];
                    $street = $addressDetails['street_name'];
                    $prop   = $addressDetails['property_name'];
                    $city   = $addressDetails['city'];
                    $zip    = $addressDetails['postal_code'];

                    if ((!empty($block)) && (!empty($room)) && (!empty($street))) {
                      
                   //   echo strtolower($room) . ' ';
                   //   echo strtolower($block) . ' ';
                   //   echo strtolower($street) . ' ';
                   //   echo strtolower($prop) . ' ';
                   //   echo strtolower($city) . ' ';
                   //   echo strtolower($zip) . ' ';

                   //   echo '<button type="button" class="btn btn-info btn-xs" data-toggle="modal" data-target="#myModal">update address</button>';
                  ?>
                    
                  <?php
                  } else {
                  ?>
                  
                  <?php 
                }
                }*/

               // print_r( $usrAddressDetails[0]); 
                

                
                ?>
              
              
              <hr>

              <strong><i class="fa fa-map-marker margin-r-5"></i> location</strong>
              <?php

                    $locationDetails  = Yii::app()->db->createCommand()
                        ->select('street_name as street , city as city , property_name as property , X(geo_location) as lats , Y(geo_location) as longs ')
                        ->from('user_address')
                        ->where('user_id = '. $users_id . ' AND is_default = 1')
                        ->queryRow();

                    $street   = $locationDetails['street'];
                    $city     = $locationDetails['city'];
                    $propNam  = $locationDetails['property']; 

                    $latitude = $locationDetails['lats'];
                    $longitude  = $locationDetails['longs'];


              ?>
              <?php  if (!empty($latitude) && !empty($longitude) && !empty($city)) { ?>
              <div id="map-canvas" class="circle-text">
                <div id="googleMap">

                <iframe
  width="600"
  height="450"
  frameborder="0" style="border:0"
  src="https://www.google.com/maps/embed/v1/place?q=<?php echo $latitude; ?>,<?php echo $longitude; ?>&key=AIzaSyCkL3rDUuCM71JE2--vQNg52pMOc9Aud44
    " allowfullscreen>
</iframe>

                </div>
              </div>
              <?php  } ?>           
              
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->

        <div class="col-md-9">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#activity" data-toggle="tab">bookings details</a></li>
              <li><a href="#timeline" data-toggle="tab">review details</a></li>
              <li><a href="#settings" data-toggle="tab">edit profile</a></li>
              <li><a href="#promo-used" data-toggle="tab">promo code used</a></li>
              
            </ul>
            <div class="tab-content">
              <div class="active tab-pane" id="activity">
                <?php echo $this->renderPartial('bookings_details', array('bookingmodel'=>$bookingmodel)); ?>
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="timeline">
                <!-- The timeline -->
                <?php 
                $reviewCount  = count($usrReviewDetails);
                if ($reviewCount == 0) {
                  echo "no reviews";
                }
                foreach ($usrReviewDetails as $reviewDetails) { ?>
                <ul class="timeline timeline-inverse">
                  <!-- timeline time label -->
                  <li class="time-label">
                        <span class="bg-red">
                          <?php
                          if (!empty($reviewDetails['updated'])) {
                            $dateCreated  = strtotime($reviewDetails['updated']);
                            echo strtolower(date('d M Y ',$dateCreated));
                            $time     = strtotime($reviewDetails['updated']);
                          } else {
                            $dateCreated  = strtotime($reviewDetails['created']);
                            echo strtolower(date('d M Y ',$dateCreated));
                            $time     = strtotime($reviewDetails['created']);
                          }

                          ?>
                        </span>
                  </li>
                  <!-- /.timeline-label -->
                  <!-- timeline item -->
                  <li>

                    <div class="timeline-item">
                      <span class="time"><i class="fa fa-clock-o"></i> <?php echo date('h:i a',$time); ?></span>

                      <h3 class="timeline-header">you have reviewed a booking</h3>

                      <div class="row"> 

                        <br>
                        <?php 
                          $image    = $reviewDetails['image'];
                          $fbImage  = $reviewDetails['facebook_image'];
                          $noImage  = Yii::app()->params['profileImageBucketUrl'].'86ad2b0b-7acc-5122-8701-4770c6526e1f.jpg';
                          $rating   = $reviewDetails['rating'];
                          $comment  = $reviewDetails['comment'];
                          $fname    = $reviewDetails['first_name'] ;
                          $image  = Yii::app()->params['therapistImageBucketUrl'].$image;
                        ?>
                        <div class="col-md-2">
                        <?php if (!empty($reviewDetails['image'])) { ?>
                        <img class="img-responsive img-circle" src="<?php echo $image; ?>" alt="therapist profile picture" style="width:70px;height:70px;">
                        <?php } else if (!empty($fbImage)) { ?>
                        <img class="img-responsive img-circle" src="<?php echo $fbImage; ?>" alt="therapist profile picture" style="width:70px;height:70px;">
                        <?php } else { ?>
                        <img class="img-responsive img-circle" src="<?php echo $noImage; ?>" alt="therapist profile picture" style="width:70px;height:70px;">
                        <?php } ?>

                        </div>
                        <div class="col-md-10">
                        <p class="profile-username" style="font-size:17px; margin-bottom:0px;"><?php echo strtolower($fname); ?></p>
                        <p style="font-size:17px; margin-bottom:0px;">
                                 
                        <?php 
                            
                            $stars = '';
                            $class = (!$small) ? 'icon-2x' : '';
                            for ($i = 1; $i <= $rating; $i++) {
                                $stars .= '<i class="fa fa-star" style="color:#f1c40f"></i>';
                            }

                            if ($rating - floor($rating)) {
                                //Means there is a half star
                                $stars .= '<i class="fa fa-star-half-full" style="color:#f1c40f"></i>';
                                $i ++;
                            }

                            for ($i; $i <= 5; $i++) {
                                $stars .= '<i class="fa fa-star-o" style="color:#f1c40f"></i>';
                            }

                            echo $stars;

                            //$ratingCount  = $rating - floor($rating) ;


                            
                        ?>
                      </p>
                        <p ><?php echo strtolower($comment); ?></p>
                      </div>
                    </div>
                    </div>
                    <br>
                    <?php } ?>
                  </li>
                </ul>
              </div>
              <!-- /.tab-pane -->

              <div class="tab-pane" id="settings">
                <?php echo $this->renderPartial('_form_userview', array('model'=>$model)); ?>
                
              </div>
              <!-- /.tab-pane -->
              
              <div class="tab-pane" id="promo-used">
                <?php echo $this->renderPartial('promocode_details', array('promocodeModel'=>$promocodeModel)); ?>
              
              </div>
              <!-- /.tab-pane promo used-->

            </div>
            <!-- /.tab-content -->
          </div>
          <!-- /.nav-tabs-custom -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

    </section>
    <?php  

     // $addressModel = UserAddress::model()->find(array('condition'=>'user_id=:id AND is_default = 1','params'=>array(':id'=>$userAddressModel->user_id),));
    ?>

    <?php 
      
    ?>

   
    <?php 
      //echo $this->renderPartial('_form_add_address', array('userAddressModel'=>$addressModel));  
    ?>

    <script type="text/javascript">
    jQuery(function ($) {
        jQuery(document).on("change", '#pageSize', function () {
            $.fn.yiiGridView.update('customers-grid', {data: {pageSize: $(this).val()}});
        });
    });

    
$(document).ready(function () {

    $('body').on('click', '.therapist-link', function (e) {

            var url = $(this).attr('href');
            var booking_id = $(this).data('book_id');
            $.ajax({
                type: "POST",
                url: url,
                data: {booking_id: booking_id},
            })
                    .done(function (data) {
                        $('#therapist-details-modal').modal();
                        $('#therapist-details-modal #tlist').html(data);
                        e.preventDefault();
                    });
            e.preventDefault();
        });

});


</script> 

