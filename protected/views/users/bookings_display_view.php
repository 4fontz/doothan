
<?php
       
$this->breadcrumbs = array(
    'appointment management',
);

$this->menu = array(
    array('label' => 'List Users', 'url' => array('index')),
    array('label' => 'Create Users', 'url' => array('create')),
);
?>
<section class="content">
      <div class="row">
        <div class="col-md-3">
         <?php
                  $details   = $userDetails;
                  
                  $bkngsType = $details[0]['type'];
                  $bkngId    = $details[0]['booking_id'];

                  $bookingId = $id;
                  
                  $usr_id    = $details[0]['id'];
                  $userId    = $details[0]['user_id'];
                  $usr_img   = $details[0]['image'];
                  $image     = Yii::app()->params['profileImageBucketUrl'].$usr_img;
                  $fbImage   = $details[0]['facebook_image'];
                  $noImage   = Yii::app()->params['profileImageBucketUrl'].'86ad2b0b-7acc-5122-8701-4770c6526e1f.jpg';    
                  $name      = $details[0]['first_name'];
                  $bkngDate  = $details[0]['booked_on'];
                  $updatedDate  = $details[0]['updated_on'];
                  $inProgressDate = $details[0]['updated_on'];
                  $status    = $details[0]['status']; 
            
                  $mltplBkng    = Yii::app()->db->createCommand()
                            ->select('* , t2.status as status , t2.id as booking_id')
                            ->from('bookings t2')
                            ->leftJoin('users t1', 't2.user_id = t1.id')
                            ->where('t2.related_booking_id = ' . $bookingId . ' AND t2.status != 6');
                  $mltplBkng    = $mltplBkng->queryAll();

                  
                  

                  $rejectedDetails  = BookingTherapist::model()->findAll(array('condition'=>"booking_id = $bookingId AND is_completed = 0"));
                  $rejectedDate     = $rejectedDetails[0]['rejected_on'];


                  if (strtotime($updatedDate) > strtotime($bkngDate)) {
                    $bookingDate  = $updatedDate;
                  } else {
                    $bookingDate  = $bkngDate;
                  }
                  $bookingDates = strtotime($bookingDate);
                  if ($bookingDate != '0000-00-00 00:00:00' && !empty($bookingDate)) {
                      $bookingTime  = Common::getTimezone($bookingDate,'h: i a');
                      $bookingDate  = Common::getTimezone($bookingDate,'d M y - h: i a');
                  } else {
                      $bookingDate  = '';
                      $bookingTime  = '';
                  }

                  if ($status >= 2) {
                      $inProgressTime   = Common::getTimezone($inProgressDate,'h: i a');
                      $inProgressDate   = Common::getTimezone($inProgressDate,'d M y - h:i a');
                  } else {
                      $inProgressDate   = '';
                      $inProgressTime   = '';
                  }

                   if ($status == 4) {
                      $rejectedTime   = Common::getTimezone($rejectedDate,'h: i a');
                      $rejectedDate   = Common::getTimezone($rejectedDate,'d M y - h:i a');
                  } else {
                      $rejectedTime   = '';
                      $rejectedDate   = '';
                  }
                  //echo '<pre>'; print_r($rejectedDetails[0]['rejected_on']); echo '</pre>'; die();
                  $confirmedDate  = $approvedDate['date'];
                  $confirmedTime  = $approvedDate['time'];

                  $completedDate  = $details[0]['completed_on'];
                  if ($completedDate != '0000-00-00 00:00:00' && !empty($completedDate)) {
                      $completedTime  = Common::getTimezone($completedDate,'h: i a');
                      $completedDate  = Common::getTimezone($completedDate,'d M y - h: i a');
                  } else {
                      $completedDate  = '';
                      $completedTime  = '';
                  }
                  $massageDuration  = $details[0]['massage_duration'];
                  $additionalNotes  = $details[0]['additional_notes'];
                  $appointmentDate  = $details[0]['appointment_on'];
                  $massageType      = $details[0]['massage_type'];
                  $package          = $details[0]['package'];
                  $bookingAddress   = $details[0]['address_id'];
                  $therapistImage   = $therapistDetails['image']; 
                  $therapistImg     = Yii::app()->params['therapistImageBucketUrl'].$therapistImage;
                  $therapistName    = trim(strip_tags($therapistDetails['name']));
                  $therapistFbImage  = $therapistDetails['fbimage'];
                  $therapistId      = $therapistDetails['id'];
                  $usrAddressDetails = Common::getUserAddressDetails($userId);
                  $massageDetails = Common::getMassageType($massageType);
                 
                  $bookingDate    = strtolower($bookingDate);
                  $confirmedDate  = strtolower($confirmedDate);
                  $completedDate  = strtolower($completedDate);
                  $inProgressDate = strtolower($inProgressDate);

         ?>
          <!-- Profile Image -->
          <div class="box box-primary">
            <div class="box-body box-profile">

              <?php if (!empty($usr_img)) { ?>
              <img class="profile-user-img img-responsive img-circle" src="<?php echo $image; ?>" alt="User profile picture">
              <?php  } else if (!empty($fbImage)) { ?>
              <img class="profile-user-img img-responsive img-circle" src="<?php echo $fbImage; ?>" alt="User profile picture">
              <?php } else { ?>
              <img class="profile-user-img img-responsive img-circle" src="<?php  echo $noImage; ?>" alt="User profile picture">
              <?php  } ?>
              <h3 class="profile-username text-center"><?php echo $name; ?></h3>

              <ul class="list-group list-group-unbordered">
                <li class="list-group-item">
                  <b>booking date</b> <p><a><?php echo $bookingDate; ?></a></p>
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
              <strong><i class="fa fa-book margin-r-5"></i> address</strong>
              <p class="text-muted">
                <?php 

                $message = $usrAddressDetails['address']; 
                $position = 25;
               echo  $post = substr($message, 0, $position); 
                ?>
              </p>
              
              <hr>

              <strong><i class="fa fa-map-marker margin-r-5"></i> location</strong>

              <p class="text-muted"><?php echo $usrAddressDetails['city']; ?></p>
              
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->

        <div class="col-md-9">
          <div class="nav-tabs-custom">
            <div class="tab-content">
              <div class="active tab-pane" id="timeline">
                <!-- The timeline -->
                <ul class="timeline timeline-inverse">
                  <!-- timeline time label -->
               <?php  if ($status == 0 ) {  ?>
                  
                  <li class="time-label">
                      <span class="bg-aqua" >
                        waiting for therapist assign
                      </span>
                  </li>
                  <!-- /.timeline-label -->
                  <!-- timeline item -->
                        <li>
                          <div class="timeline-item">
                            <span class="time"><i class="fa fa-clock-o"></i> <?php echo 'pending booking'; ?></span>
                            <h3 class="timeline-header">
                              <?php echo 'waiting for confirmation';   ?>
                            </h3>
                          </div>
                        </li>
                  
                  <?php } ?>
                    
                   <?php if ($status != 3 && $status != 0) { ?>        
                  <li class="time-label">
                        <span class="bg-green" >
                          confirmed bookings
                        </span>
                  </li>
                  <!-- /.timeline-label -->
                  <!-- timeline item -->
                        <li>
                          <div class="timeline-item">
                            <?php if (!empty($confirmedTime)) { ?>
                            <span class="time"><i class="fa fa-clock-o"></i> <?php  echo $confirmedTime;  ?></span>
                            <?php } ?>
                            <h3 class="timeline-header">
                              <?php if (!empty($confirmedDate)) { echo $confirmedDate; } ?>
                            </h3>
                          </div>
                        </li>
                        <?php } ?>

                        <?php if ($status != 1 && $status != 0) { ?>
                        <li class="time-label">
                            <span class="bg-blue">
                              bookings in progress
                            </span>
                        </li>
    
                        <li>
                         <div class="timeline-item">
                            <?php if (!empty($inProgressTime)) { ?>
                            <span class="time"><i class="fa fa-clock-o"></i> <?php echo $inProgressTime; ?></span>
                            <?php } ?>
                            <h3 class="timeline-header">
                              <?php if (!empty($inProgressDate)) { echo $inProgressDate; }  ?>
                            </h3>
                          </div>
                        </li>
                        <?php  } ?>

                        <?php if ($status == 5) { ?>
                         
                        <li class="time-label">
                              <span class="bg-yellow">
                                completed bookings
                              </span>
                        </li>
                  <!-- /.timeline-label -->
                  <!-- timeline item -->
                        <li>
                          <div class="timeline-item">
                            <?php if (!empty($completedTime)) { ?>
                            <span class="time"><i class="fa fa-clock-o"></i> <?php  echo $completedTime; ?></span>
                            <?php } ?>
                            <h3 class="timeline-header">
                              <?php 
                                if (!empty($completedDate)) { echo $completedDate; } 
                                ?>
                            </h3>
                          </div>
                        </li>
                        <?php  } ?>

                      <?php if ($status == 4) {  ?>
                       <li class="time-label">
                              <span class="bg-maroon">
                                rejected bookings
                              </span>
                        </li>
                  <!-- /.timeline-label -->
                  <!-- timeline item -->
                        <li>
                          <div class="timeline-item">
                            <?php if (!empty($rejectedTime)) { ?>
                            <span class="time"><i class="fa fa-clock-o"></i> <?php  echo $rejectedTime; ?></span>
                            <?php } ?>
                            <h3 class="timeline-header">
                              <?php 
                                if (!empty($rejectedDate)) { echo $rejectedDate; } 
                                ?>
                            </h3>
                          </div>
                        </li>
                   <?php   } ?>

              </ul>
              </div>
              <!-- /.tab-pane -->

              <div class="well well-lg">
                <div class="row">
                  <div class="col-md-2">
                
                  <?php 
                  if (!empty($therapistName)) { ?>
                  <p>therapist</p>
                      <?php if (!empty($therapistImage)) { ?>
                      <img class="profile-custom-img img-circle" src="<?php echo $therapistImg; ?>" alt="therapist profile picture">
                      <?php  } else if (!empty($therapistFbImage)) { ?>
                      <img class="profile-custom-img img-responsive img-circle" src="<?php echo $therapistFbImage; ?>" alt="therapist profile picture">
                      <?php } else { ?>
                      <img class="profile-custom-img img-responsive img-circle" src="<?php  echo $noImage; ?>" alt="therapist profile picture">
                      <?php  } ?>
                      <h3 class="profile-username"><?php echo $therapistName; ?></h3>
                      <?php echo Common::starRating($therapistId); ?>

                  <?php } else { 
                      echo '<b>therapist not assigned </b> ';
                      
                    }
                  ?>
                </div>

                <div class="col-md-5">
                  <?php if (!empty($massageDuration)) { ?>
                  <b> booking details : </b>
                  <?php echo $massageDuration . ' minutes ' .   "<b>$massageDetails</b>"; ?>
                  <?php if (!empty($appointmentDate) && ($appointmentDate != '0000-00-00 00:00:00')) {
                            $appointmentDate  = strtotime($appointmentDate);
                            $appointmentDate  = date('d M y - h: i a',$appointmentDate);;
                            echo ' on ' .strtolower($appointmentDate);
                  ?>
                  
                  <?php } }?>

                  <?php if (!empty($additionalNotes)) {
                  ?>
                  <br>
                  <br>
                  <b>booking notes : </b>
                  <?php
                  $bookingnotes = json_decode($additionalNotes, true);
                 $gateDoor = $bookingnotes['gate_door'];
                 $tableSheet = $bookingnotes['table_sheet'];
                 $Dog = $bookingnotes['dog'];
                 $cat = $bookingnotes['cat'];
                 $stairs = $bookingnotes['stairs'];
                 $notes = $bookingnotes['notes'];
                 //gatedoor content
                  if ($gateDoor != '') {
                    $gatedoor_notes = $gateDoor;
                  }else{
                    $gatedoor_notes = 'nil';
                  }
                  //tablesheet content
                  if ($tableSheet != '') {
                    $tableSheet_notes = $tableSheet;
                  }else{
                    $tableSheet_notes = 'nil';
                  }
                  //dog content
                  if ($Dog != '') {
                    $Dog_notes = $Dog;
                  }else{
                    $Dog_notes = 'nil';
                  }
                  //cat content
                  if ($cat != '') {
                    $cat_notes = $cat;
                  }else{
                    $cat_notes = 'nil';
                  }
                  //stairs content
                  if ($stairs != '') {
                    $stairs_notes = $stairs;
                  }else{
                    $stairs_notes = 'nil';
                  }
                  //notes content
                  if ($notes != '') {
                    $content_notes = $notes;
                  }else{
                    $content_notes = 'nil';
                  }
                 ?>
                 gate_door : <?php echo $gatedoor_notes; ?> ,table_sheet : <?php echo $tableSheet_notes; ?> ,
                  dog : <?php echo $Dog_notes; ?> , cat : <?php echo $cat_notes;  ?>  , stairs: <?php echo $stairs_notes; ?>, notes: <?php echo $content_notes; ?>


                  <?php  }
                  ?>

                </div>

                <div class="col-md-5">
                  <?php if (!empty($bookingAddress)) {
                  ?>
                  
                  <b>booking address : </b>
                  <?php 
                   $message =strtolower(Common::getBookingAddress($bookingAddress));
                   $position = 50;
                   $post = substr($message, 0, $position); 
                   echo $post; 
                    }
                  ?>

                  <?php if (!empty($package)) {
                  ?>
                  <br>
                  <br>
                  <b>package selected : </b>
                  <?php echo strtolower(Common::getPackageDetails($package)); ?>
                  <?php } ?>

                  
                </div>

                
                </div>
                <br><br>

                <?php foreach ($mltplBkng as $multplBooking) { 
                        
                        $massageType  = $multplBooking['massage_type'];
                        $userId       = $multplBooking['id'];
                        $usrAddressDetails = Common::getUserAddressDetails($userId);
                        $massageDetails = Common::getMassageType($massageType);
                        $massageDuration  = $multplBooking['massage_duration'];
                        $appointmentDate  = $multplBooking['appointment_on'];

                        $package          = $multplBooking['package'];

                        $booking_id     = $multplBooking['booking_id'];
                        $therapistDetails   = Yii::app()->db->createCommand()
                                        ->select('*')
                                        ->from('users t1')
                                        ->leftJoin('booking_therapist t2', 't2.therapist_id = t1.id')
                                        ->where('t2.booking_id = ' . $booking_id);

                        $therapistDetails    = $therapistDetails->queryAll();
                        $therapistImage      = $therapistDetails[0]['image'];
                        $therapistImg        = Yii::app()->params['therapistImageBucketUrl'].$therapistImage;
                        $therapistFbImage    = $therapistDetails[0]['facebook_image'];
                        $therapistFirstname  = $therapistDetails[0]['first_name'];
                        $therapistLastname   = $therapistDetails[0]['last_name'];
                        $therapistName       = $therapistFirstname . ' ' . $therapistLastname;
                        $therapistId         = $therapistDetails[0]['therapist_id'];

                        $bkngNotes           = $multplBooking['additional_notes'];
                        //$therapist_details   = array('name' => $therapistName , 'image' => $therapistImage,'fbimage' =>$therapistFbImage,'id'=>$therapistId );
                       // print_r($therapist_details);
                        
                        //     // print_r($multplBooking);
                       //  die();

              ?>    

              <?php  if ($bkngsType == 'couple') { ?>

                <div class="row">
                  <div class="col-md-2">
                
                  <?php 
                  if (!empty($therapistName)) { ?>
                  
                      <?php if (!empty($therapistImg)) { ?>
                      <img class="profile-custom-img img-circle" src="<?php echo $therapistImg; ?>" alt="therapist profile picture">
                      <?php  } else if (!empty($therapistFbImage)) { ?>
                      <img class="profile-custom-img img-responsive img-circle" src="<?php echo $therapistFbImage; ?>" alt="therapist profile picture">
                      <?php } else { ?>
                      <img class="profile-custom-img img-responsive img-circle" src="<?php  echo $noImage; ?>" alt="therapist profile picture">
                      <?php  } ?>
                      <h3 class="profile-username"><?php echo $therapistName; ?></h3>
                      <?php echo Common::starRating($therapistId); ?>

                  <?php } else { 
                      echo '<b>therapist not assigned </b> ';
                      
                    }
                  ?>
                </div>


                <div class="col-md-5">
                  <?php if (!empty($massageDuration)) { ?>
                  <b> booking details : </b>
                  <?php echo $massageDuration . ' minutes ' .   "<b>$massageDetails</b>"; ?>
                  <?php if (!empty($appointmentDate) && ($appointmentDate != '0000-00-00 00:00:00')) {
                            $appointmentDate  = strtotime($appointmentDate);
                            $appointmentDate  = date('d M y - h: i a',$appointmentDate);;
                            echo ' on ' .strtolower($appointmentDate);
                  ?>
                  
                  <?php } }?>


                  <?php if (!empty($bkngNotes)) {
                  ?>
                  <br>
                  <br>
                  <b>booking notes : </b>
                  <?php
                  $bookingnotes = json_decode($bkngNotes, true);
                 $gateDoor = $bookingnotes['gate_door'];
                 $tableSheet = $bookingnotes['table_sheet'];
                 $Dog = $bookingnotes['dog'];
                 $cat = $bookingnotes['cat'];
                 $stairs = $bookingnotes['stairs'];
                 $notes = $bookingnotes['notes'];
                 //gatedoor content
                  if ($gateDoor != '') {
                    $gatedoor_notes = $gateDoor;
                  }else{
                    $gatedoor_notes = 'nil';
                  }
                  //tablesheet content
                  if ($tableSheet != '') {
                    $tableSheet_notes = $tableSheet;
                  }else{
                    $tableSheet_notes = 'nil';
                  }
                  //dog content
                  if ($Dog != '') {
                    $Dog_notes = $Dog;
                  }else{
                    $Dog_notes = 'nil';
                  }
                  //cat content
                  if ($cat != '') {
                    $cat_notes = $cat;
                  }else{
                    $cat_notes = 'nil';
                  }
                  //stairs content
                  if ($stairs != '') {
                    $stairs_notes = $stairs;
                  }else{
                    $stairs_notes = 'nil';
                  }
                  //notes content
                  if ($notes != '') {
                    $content_notes = $notes;
                  }else{
                    $content_notes = 'nil';
                  }
                 ?>
                 gate_door : <?php echo $gatedoor_notes; ?> ,table_sheet : <?php echo $tableSheet_notes; ?> ,
                  dog : <?php echo $Dog_notes; ?> , cat : <?php echo $cat_notes;  ?>  , stairs: <?php echo $stairs_notes; ?>, notes: <?php echo $content_notes; ?>


                  <?php  }
                  ?>

                </div>
                

                <div class="col-md-5">
                  
                  <?php if (!empty($bookingAddress)) {
                  ?>
                  
                  <b>booking address : </b>
                  <?php 
                   $message =strtolower(Common::getBookingAddress($bookingAddress));
                   $position = 50;
                   $post = substr($message, 0, $position); 
                   echo $post; 
                    }
                  ?>

                  <?php if (!empty($package)) {
                  ?>
                  <br>
                  <br>
                  <b>package selected : </b>
                  <?php echo strtolower(Common::getPackageDetails($package)); ?>
                  <?php } ?>

                  

                </div>

                

                </div>
                <?php } } ?>
              </div>
              
              <?php $therImage  = $therapistDetails[0]['image']; 
                    $therName   = $therapistDetails[0]['username'];
                    $therAddress  = $therapistDetails[0]['address_id'];
              ?>

              <!-- /.tab-pane -->

              
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

    <script type="text/javascript">
    jQuery(function ($) {
        jQuery(document).on("change", '#pageSize', function () {
            $.fn.yiiGridView.update('customers-grid', {data: {pageSize: $(this).val()}});
        });
    });

</script> 

