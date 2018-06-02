<?php 
	
	$loginStatUrl   = Yii::app()->baseUrl . '/users/index?type='.$userType.'&Users[account_status]=call_verification_pending';
              $docPendurl     = Yii::app()->baseUrl . '/users/index?type='.$userType.'&Users[account_status]=documents_pending';
              $apprdurl       = Yii::app()->baseUrl . '/users/index?type='.$userType.'&Users[account_status]=approved';
              $rjctdurl       = Yii::app()->baseUrl . '/users/index?type='.$userType.'&Users[account_status]=rejected';


?>

<div class="row">
                <div class="col-xs-3 text-center" style="border-right: 1px solid #f4f4f4">
                  <a href="<?php echo $docPendurl; ?>" >
                  	<input type="text" class="knob" data-readonly="true" value="<?php echo $docPending; ?>" data-width="60" data-height="60"
                         data-fgColor="#39CCCC">
                  </a>

                  <div class="knob-label">Documents Pending</div>
                </div>
                <!-- ./col -->
                <div class="col-xs-3 text-center" style="border-right: 1px solid #f4f4f4">
                  <a href="<?php echo $loginStatUrl; ?>" >
                  	<input type="text" class="knob" data-readonly="true" value="<?php echo $callVerfPend; ?>" data-width="60" data-height="60"
                         data-fgColor="#39CCCC">
                  </a>       
                  <div class="knob-label">Call Verification Pending</div>
                </div>
                <!-- ./col -->
                <div class="col-xs-3 text-center">
                  <a href="<?php echo $apprdurl; ?>" > 
                  	<input type="text" class="knob" data-readonly="true" value="<?php echo $rejectedUser; ?>" data-width="60" data-height="60"
                         data-fgColor="#39CCCC">
                  </a>       
                  <div class="knob-label">Approved</div>
                </div>
                <div class="col-xs-3 text-center">
                  <a href="<?php echo $rjctdurl; ?>" >
                  	<input type="text" class="knob" data-readonly="true" value="<?php echo $apprUser; ?>" data-width="60" data-height="60"
                         data-fgColor="#39CCCC">
                  </a>
                  <div class="knob-label">Rejected</div>
                </div>
                <!-- ./col -->
              </div>
              
              <!-- /.row -->
