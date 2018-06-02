<?php
$this->breadcrumbs = array(
    'Request management','Service Charge'
);

$this->menu = array(
    array('label' => 'List Users', 'url' => array('index')),
    array('label' => 'Create Users', 'url' => array('create')),
);

$userDetails  = $model->user;
$userName     = $userDetails->first_name;
$userlastNanme = $userDetails->last_name;
$cityId       = $model->to_city;
$toAddress    = $model->to_address;
$stateName    = $model->to_state;
$pinCode      = $model->to_pincode;
if($model->doothan_id!=0){
    $doothan_Details = UserAddress::model()->findByAttributes(array('user_id'=>$model->doothan_id));
    $doothan_datas = Users::model()->findByPk($model->doothan_id);
    $doothan_address  = "<b>Name : </b>".$doothan_datas->first_name." ".$doothan_datas->last_name."<br/><br/><b>Address : </b>".$doothan_Details->address.' '.$doothan_Details->city. '<br/> '.$doothan_Details->state.' '.$doothan_Details->postal_code;
}else{
    $doothan_address = "Doothan Not Found";
}
$city         = Cities::model()->findByAttributes(array('city_id'=>$cityId));
$cityName     = $city->city_name;
$userId       = $model->user_id;
$dropbox_details = Users::model()->findByPk($model->dropbox_id);
$userAddressDetails  = UserAddress::model()->findByAttributes(array('user_id'=>$model->dropbox_id));
$userAddress  = $userAddressDetails->address;
$userState    = $userAddressDetails->state;
$userCity     = $userAddressDetails->city;
$userCountry  = 'India';
$userPost     = $userAddressDetails->postal_code; 
$address  = $userAddress.' '.$userCity. ' '.$userState . ' '.$userCountry.' '.$userPost;
$from_address  = "<b>Name : </b>".$dropbox_details->first_name." ".$dropbox_details->last_name."<br/><br/><b>Address : </b>".$userAddress.' '.$userCity. '<br/> '.$userState . ' '.$userCountry.' '.$userPost;
$to_address  = "<b>Name : </b>".$userName." ".$userlastNanme."<br/><br/><b>Address : </b>".$toAddress .' ' . $cityName.' <br/>' . $stateName . ' '.$pinCode;
$delivery_address  = $toAddress .' ' . $cityName.' ' . $stateName . ' '.$pinCode;
$fareFrom = $userPost;
$fareTo = $pinCode;
$address  = wordwrap($address, 30, "<br />\n",true);
$bookingDate  = Helper::dateFormat($model->created_on);
$city_name  = !empty($userCity) ? $userCity : $cityName;
$uploadUrl    = Yii::app()->params["uploadUrl"];
$usrImage     = $uploadUrl.$userDetails->image;
$noImage      = Yii::app()->request->baseUrl.'/images/no-image.jpg';
if($userDetails->image){
    $img = Yii::app()->params['profileImageBucketUrl'].$userDetails->image;
}else{
    $img = Yii::app()->request->baseUrl.'/images/no-image.jpg';
}
if(!empty($model->image)){
    $requestImg = Yii::app()->request->baseUrl.'/uploads/request/'.$model->image;
}else{
    $requestImg = Yii::app()->request->baseUrl.'/images/empty-product.png';
}
if($model->image){
    if(Yii::app()->params['requestImageBucket'].$model->image){
        $request_url=Yii::app()->params['requestImageBucketUrl'].$model->image;
    }else{
        $request_url=Yii::app()->request->baseUrl.'/images/no-img.png';
    }
}else{
    $request_url=Yii::app()->request->baseUrl.'/images/no-img.png';
}

$itemName     = $model->item_details;
$deliveryDate = Helper::dateFormat($model->request_date);
$delDate      = Helper::dateFormat($deliveryDate);

$settings = Settings::model()->find();
//$default_weight_limit=$settings->default_weight_limit;
//$default_distance_limit=$settings->default_distance_limit;
$default_weight_charge=$settings->default_weight_charge;
$default_distance_charge=$settings->default_distance_charge;

$wcharge=$default_weight_charge;
if(intval($model->distance) >=0){
    //$dcharge=(intval($model->distance)*2 *$model->rate_per_km);
    $dcharge=(intval($model->distance)*$model->rate_per_km);
    $dcharge_mine=(intval($model->distance)*$model->rate_per_km);
  }else{
    $dcharge=$default_distance_charge;
  }
$charge=$model->base_amount+$dcharge;
 //redeem coupon amount
  if($model->coupon_amount > 0){
    $charge=$charge-intval($model->coupon_amount);
  }
  //discount calculation
  if($model->discount > 0){
    $charge=$charge-intval($model->discount);
  }
$charge=$charge+$model->weight;
if($model->gst > 0){
  $gst_amount=$charge/100*$model->gst;
}
$feeGst=$charge+$gst_amount;
if($model->distance==0){
   $distance=Helper::getLocationDistance(array($fareFrom,$fareTo));
   $distance  = $distance*2;
 }else{
  $distance=$model->distance;
 }
 $km_diatnce = trim(str_replace('km', '', $distance));
 $km_diatnce = round(trim(str_replace('km', '', $distance)));

 
 $discountCheck = Request::model()->findByAttributes(array('user_id'=>$model->user_id));
 if(count($discountCheck)==1){
     $discount = 0;
 }else{
     $discount = 20;
 }
 
?>

<section class="content">
      <div class="row">
 <?php if (Yii::app()->user->hasFlash('success')): ?>
    <div class="alert alert-success">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <?php echo Yii::app()->user->getFlash('success'); ?>
    </div>
<?php endif; ?>
<?php if (Yii::app()->user->hasFlash('error')): ?>
    <div class="alert alert-error">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <?php echo Yii::app()->user->getFlash('error'); ?>
    </div>
<?php endif; ?>
<br/>

        <div class="col-md-12">
          <div class="box box-primary">
            <div class="text-center">
              <!-- <i class="fa fa-shopping-basket" aria-hidden="true"></i> -->
              <h2><u>Service Charge</u></h2>
            </div>
            <br>
            <div class="row item-info">
              <div class="row invoice-info">
                <div class="col-sm-3 invoice-col">
                  <h4>Delivery Address</h4>
                  <address class="item-details-text">
                    <?php echo $to_address; ?>
                  </address>
                </div>
                <div class="col-sm-3 invoice-col">
                 <h4> Doothan Address</h4>
                  <address class="item-details-text" id="doothan_address" style="padding-top: 12px;">
                   <?php echo $doothan_address; ?>
                  </address>
                </div>
                <div class="col-sm-3 invoice-col">
                 <h4> Dropbox Address</h4>
                  <address class="item-details-text">
                   <?php echo $from_address; ?>
                  </address>
                </div>
                <div class="col-sm-3 invoice-col">
                  <b>Booking Code #<?php echo $model->request_code ?></b><br>
                  <b>Delivery Item:</b> <p class="item-details-text"><?php echo $itemName; ?></p><br>
                  <b>Payment date: </b><?php echo $delDate; ?><br>
                </div>
          	</div>
         </div>
        <div class="row item-info">
            <div class="col-md-12">
              <div class="col-md-6">
              	  <p id="success_found_doothan"></p>
                  <p><b>Total Distance Between Delivery Address and Dropbox Address : <?php echo $distance; ?></b></p>
              </div>
              <?php if($model->doothan_id==0){?>
                  <div class="col-md-6">
                      <p style="color:red;"><b>Couldn't find any doothan,please try after some time..!</b></p>
                  </div>
              <?php }?>
            </div>
        </div>
	<div class="row item-info">
   		<div class="space_20px"></div>
  			<div class="col-md-6">
     			<p class="lead">Fare Calculation</p>
                    <?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
                      'id'=>'feedback-form',
                      'enableAjaxValidation'=>true,
                      'htmlOptions'=>array('enctype'=>'multipart/form-data','class'=>'form-horizontal'),
                     )); ?>    
  				  <?php echo $form->errorSummary($model); ?>
                  <div class="col-md-12">
                   <div class="form-group">
                      <?php echo $form->labelEx($model,'base_amount',array('class'=>'col-sm-2 col-md-4 control-label align-left-class')); ?>
                      <div class="col-sm-10 col-md-4">
                       <?php echo $form->numberField($model,'base_amount',array('class'=>'form-control class-small','maxlength'=>100,'placeholder' => 'Service Charge', 'min' =>'50', 'type' => 'number')); ?>
                          <?php echo $form->error($model,'base_amount'); ?>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-12">
                   <div class="form-group">
                      <?php echo $form->labelEx($model,'distance',array('class'=>'col-sm-2 col-md-4 control-label align-left-class')); ?>
                      <div class="col-sm-10 col-md-4">
                        <input type="number" class="form-control class-small" name="Request[distance]" value="<?php echo $km_diatnce; ?>" maxlength="1000" placeholder = 'Total Distance' min=0> 
                       <?php //echo $form->textField($model,'distance',array('class'=>'form-control class-small','maxlength'=>100,'placeholder' => 'Distance', 'min' =>'0')); ?>
                          <?php echo $form->error($model,'distance'); ?>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-12">
                   <div class="form-group">
                      <?php echo $form->labelEx($model,'rate_per_km',array('class'=>'col-sm-2 col-md-4 control-label align-left-class')); ?>
                      <div class="col-sm-10 col-md-4">
                         <?php echo $form->numberField($model,'rate_per_km',array('class'=>'form-control class-small','maxlength'=>100,'min' =>'0', 'type' => 'number','step'=>'any','value'=>$default_distance_charge)); ?>
                        <?php echo $form->error($model,'rate_per_km'); ?>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-12">
                   <div class="form-group">
                      <?php echo $form->labelEx($model,'weight',array('class'=>'col-sm-2 col-md-4 control-label align-left-class')); ?>
                      <div class="col-sm-10 col-md-4">
                        <?php echo $form->textField($model,'weight',array('class'=>'form-control class-small','maxlength'=>100,'placeholder' => 'Weight', 'min' =>'0','value'=>$default_weight_charge)); ?>
                      </div>
                    </div>
                  </div> 
                  <div class="col-md-12">
                   <div class="form-group">
                      <?php echo $form->labelEx($model,'coupon_amount',array('class'=>'col-sm-2 col-md-4 control-label align-left-class')); ?>
                      <div class="col-sm-10 col-md-4">
                         <?php echo $form->numberField($model,'coupon_amount',array('class'=>'form-control class-small','maxlength'=>100,'min' =>'0', 'type' => 'number')); ?>
                        <?php echo $form->error($model,'coupon_amount'); ?>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-12">
                   <div class="form-group">
                      <?php echo $form->labelEx($model,'discount',array('class'=>'col-sm-2 col-md-4 control-label align-left-class')); ?>
                      <div class="col-sm-10 col-md-4">
                        <?php echo $form->numberField($model,'discount',array('class'=>'form-control class-small','maxlength'=>100,'min' =>'0', 'type' => 'number','value'=>$discount)); ?>
                        <?php echo $form->error($model,'discount'); ?>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-12">
                   <div class="form-group">
                     <label class="col-sm-2 col-md-4 control-label align-left-class">Gst ( % )</label>
                      <div class="col-sm-10 col-md-4">
                        <?php echo $form->textField($model,'gst',array('class'=>'form-control class-small','maxlength'=>100,'min' =>'0','value'=>$settings->gst)); ?>
                        <?php echo $form->error($model,'gst'); ?>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-12">
                   <div class="form-group">
                      <?php echo $form->labelEx($model,'product_price',array('class'=>'col-sm-2 col-md-4 control-label align-left-class')); ?>
                      <div class="col-sm-10 col-md-4">
                       <?php echo $form->numberField($model,'product_price',array('class'=>'form-control class-small','maxlength'=>100,'placeholder' => 'Service Charge', 'min' =>'0', 'type' => 'number')); ?>
                          <?php echo $form->error($model,'product_price'); ?>
                      </div>
                    </div>
                  </div>
                  <div class="form-group col-md-12">
                    <div class="space_20px"></div>
                    <div class="col-md-4">
                    	<input type="hidden" value="<?php echo $fareFrom; ?>" name="Request[request_address]">
                     	<input type="hidden" value="<?php echo $fareTo; ?>" name="Request[delivery_address]">
                    </div>
                   <?php
                   if(($model->status=="Request Placed" || $model->status=="Waiting for payment") && $model->doothan_id!=0){?>
                   		<?php if($model->amount > 0){ 
                   		    $text = "Regenerate fare";
                   		}else{
                   		    $text = "Calculate fare";
                   		}?>
                        <div class="col-md-6" style="margin-left: 10px;" id="fare_buttons">
                          <?php $this->widget('bootstrap.widgets.TbButton', array(
                            'buttonType'=>'submit',
                            'type'=>'primary',
                              'label'=>$text,
                              'id'=>'fare_button'
                          )); ?>
                          <?php if($model->amount==0){?>
                          	<?php echo CHtml::resetButton('Reset Fare',array("id"=>'chtmlbutton','class'=>'btn btn-danger')); ?>
                          <?php }?>
                        </div>
                    <?php }?>
                    </div> 
    		<?php $this->endWidget(); ?>
  		</div>
      <div class="col-md-6">
        <?php if($model->amount > 0){ ?>
              <p class="lead">Amount</p>
    
              <div class="table-responsive">
                <table class="table">
                  <tbody>
                  <tr>
                    <th>Minimum Amount</th>
                    <td><i class="fa fa-inr" aria-hidden="true"></i> <?php echo number_format((float)$model->base_amount, 2, '.', ''); ?></td>
                  </tr>
                  <tr>
                    <th>Transportation Charge ( <?php echo $model->rate_per_km; ?> per KM)</th>
                    <td><i class="fa fa-inr" aria-hidden="true"></i> <?php echo number_format((float)$dcharge, 2, '.', ''); ?></td>
                  </tr>
                  <tr>
                    <th>Discount</th>
                    <td><i class="fa fa-inr" aria-hidden="true"></i> <?php echo number_format((float)$model->discount, 2, '.', ''); ?></td>
                  </tr>
                  <tr>
                    <th>Coupon Amount</th>
                    <td><i class="fa fa-inr" aria-hidden="true"></i> <?php echo number_format((float)$model->coupon_amount, 2, '.', ''); ?></td>
                  </tr>
                  <tr>
                    <th>Weight Charge</th>
                    <td><i class="fa fa-inr" aria-hidden="true"></i> <?php echo number_format((float)$model->weight, 2, '.', ''); ?></td>
                  </tr>
                 
                   <tr>
                    <th> Service Fee before VAT </th>
                    <td><i class="fa fa-inr" aria-hidden="true"></i> <?php 
                    //$ab =  $model->base_amount+$dcharge-($model->discount+$model->coupon_amount)+$wcharge;
                    $ab = $model->discount+$model->coupon_amount;
                    $bc = $dcharge_mine;
                    $abc = $model->base_amount + $bc - $ab + $model->weight;
                    ?><?php echo number_format((float)$abc, 2, '.', ''); ?></td>
                  </tr>
                  <tr>
                    <th>Tax ( <?php echo $model->gst;?> %)</th>
                    <td><i class="fa fa-inr" aria-hidden="true"></i> <?php echo number_format((float)$gst_amount, 2, '.', ''); ?></td>
                  </tr>
                  <tr>
                    <th> Service Fee after adding VAT(I)</th>
                    <td><i class="fa fa-inr" aria-hidden="true"></i> <?php $gst_amount_after = $gst_amount+$abc;echo number_format((float)$gst_amount_after, 2, '.', ''); ?></td>
                  </tr>
                 <tr>
                    <th> Product Price</th>
                    <td><b><i class="fa fa-inr" aria-hidden="true"></i> <?php echo number_format((float)$model->product_price, 2, '.', ''); ?></b></td>
                  </tr>
                  
                  <tr>
                    <th>Total:</th>
                    <td> &#x20b9 <b><?php echo number_format((float)$gst_amount_after+$model->product_price, 2, '.', ''); ?></b></td>
                  </tr>
                  <?php if($model->status=="Request Placed" || $model->status=="Waiting for payment"){?>
                      <tr>
                        <td></td>
                        <td><button class="btn btn-primary" type="button" name="send_notification" id="send_notification" style="float:right;">Send Notification</button></td>
                      </tr>
                  <?php }?>
                  <?php if($model->status=="Received to dropbox"){?>
                      <tr>
                        <td></td>
                        <td><button class="btn btn-primary" type="button" name="send_notification" id="send_notification_user" style="float:right;">Send notification to user</button></td>
                      </tr>
                  <?php }?>
                </tbody></table>
              </div>
              <div class="" style="padding-bottom: 20px;" id="error_notice"></div>
              <div>
              <p class="lead">Payment Methods:</p>
              <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/visa.png" alt="Visa">
              <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/mastercard.png" alt="Mastercard">
              <!-- <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/american-express.png" alt="American Express">
              <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/paypal2.png" alt="Paypal"> -->
              </div>
              <?php }?>
        </div>
  		<div class="clear"></div> 
    	<div class="space_20px"></div>
          <div class="row">
            <div class="col-xs-12  col-md-4 table-responsive">
              <table class="table table-striped">
                <thead>
                <tr>
                  <th></th>
                  <th></th>
                  <th></th>
                </tr>
                </thead>
                <tbody>
                  <tr>
                  <td></td>
                  <td>Doothan Fee </td>
                   <td><span class="btn  btn-xs btn-success"> &#x20b9 <?php echo $model->doodhan_fee; ?> </span></td>
                </tr>
                <tr>
                  <td></td>
                  <td>Drop Box Owner Fee </td>
                  <td><span class="btn  btn-xs btn-info"> &#x20b9 <?php echo $model->dropbox_fee; ?> </span></td>
                </tr>
                </tbody>
              </table>
            </div>
            <!-- /.col -->
          </div>
    	</div>
          </div>
          </div>
        </div>


    </section>  
<style>
.align-left-class{text-align:left!important;}
</style>

    <script type="text/javascript">
    jQuery(function ($) {
        jQuery(document).on("change", '#pageSize', function () {
            $.fn.yiiGridView.update('customers-grid', {data: {pageSize: $(this).val()}});
        });
    });

    $('#chtmlbutton').click(function(){

		
    });

    
    $('#send_notification').on('click',function(){
    	var result = window.confirm('Are you sure wants to send the notification ?');
        if (result == true) {
        	$('#send_notification').html('Please wait....!');
    		user_id = '<?php echo $userId?>';
    		order_id = '<?php echo $model->id?>';
    		type="0";
    		$.ajax({
        		type:'POST',
        		dataType:'json',
        		data:{'user_id':user_id,'order_id':order_id,'type':type},
        		url:'<?php echo Yii::app()->createAbsoluteUrl("request/PushNotification"); ?>',
        		success:function(response){
        			$('#send_notification').html('Send Notification');
            		if(response.message.failure==1 && response.error_msg=="NotRegistered"){
    					$('#error_notice').html("Error: User unistalled(updated) the app , system couldn't find the device id, requester must login to app once again and try it").css({'color':'red'});
        			}else if(response.message.failure==0 && response.message.success==1){
        				$('#error_notice').html('Notification successfully sent..!').css({'color':'green'});
        				$('#send_notification').attr('disabled',true);
        				$('#fare_buttons').hide();
            		}else{
            			$('#error_notice').html('Notification successfully sent..!').css({'color':'green'});
            			$('#send_notification').attr('disabled',true);
        				$('#fare_buttons').hide();
                	}
        		},error: function(jqXHR, textStatus, errorThrown) {
        			$('#send_notification').html('Error: Error While Sent Notification').css({'color':'red'});
                }
        	});
        }
    });

    $('#send_notification_user').on('click',function(){
    	var result = window.confirm('Are you sure wants to remind user about this request?');
        if (result == true) {
        	$('#send_notification_user').html('Please wait....!');
    		user_id = '<?php echo $userId?>';
    		order_id = '<?php echo $model->id?>';
    		type="1";
    		$.ajax({
        		type:'POST',
        		dataType:'json',
        		data:{'user_id':user_id,'order_id':order_id,'type':type},
        		url:'<?php echo Yii::app()->createAbsoluteUrl("request/PushNotification"); ?>',
        		success:function(response){
        			$('#send_notification_user').html('Send Notification');
            		if(response.message.failure==1 && response.error_msg=="NotRegistered"){
    					$('#error_notice').html("Error: User unistalled the app or system couldn't find the device id, requester must login to the app once again and try it").css({'color':'red'});
        			}else if(response.message.failure==0 && response.message.success==1){
        				$('#error_notice').html('Notification successfully sent..!').css({'color':'green'});
        				$('#send_notification_user').attr('disabled',true);
        				$('#fare_buttons').hide();
            		}else{
            			$('#error_notice').html('Notification successfully sent..!').css({'color':'green'});
            			$('#send_notification_user').attr('disabled',true);
        				$('#fare_buttons').hide();
                	}
        		},error: function(jqXHR, textStatus, errorThrown) {
        			$('#send_notification_user').html('Error While Sent Notification');
                }
        	});
        }
    });

    function setDoothan(param){
      $('#success_found_doothan').html('');
      var doothan_id = $(param).val();  
      var request_id = $(param).attr('id');
      $.ajax({
  		type:'POST',
  		dataType:'html',
  		data:{'doothan_id':doothan_id,'request_id':request_id},
  		url:'<?php echo Yii::app()->createAbsoluteUrl("request/Assign_doothan"); ?>',
  		success:function(response){
      		if(response!=0){
				$('#doothan_address').html(response);
				$('#success_found_doothan').html('Assigned new doothan<b>'+ text +'</b>');
            }
  		},error: function(jqXHR, textStatus, errorThrown) {
  			$('#send_notification').html('Error While Sent Notification');
          }
  	});
    }
</script> 

