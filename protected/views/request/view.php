
<?php
      
$this->breadcrumbs = array(
    'Request management',
);
?>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.2.5/jquery.fancybox.css">
<script src="//code.jquery.com/jquery-3.2.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.2.5/jquery.fancybox.min.js"></script>
<section class="content">
      <div class="row">
        <div class="col-md-3">
         <?php

            $userDetails  = $model->user;
            $userName     = $userDetails->first_name;
            $usertype     = ucfirst($userDetails->member_type);
            $cityId       = $model->to_city;
            $toAddress    = $model->to_address;
            $stateName    = $model->to_state;
            $pinCode      = $model->to_pincode;
            $city         = Cities::model()->findByAttributes(array('city_id'=>$cityId));
            $cityName     = $city->city_name;
            $userId       = $model->user_id;
            $doothan_details = Users::model()->findByPk($model->doothan_id);
            
            $userAddressDetails  = UserAddress::model()->findByAttributes(array('user_id'=>$model->dropbox_id));
            $userAddress  = $userAddressDetails->address;
            $userState    = $userAddressDetails->state;
            $userCity     = $userAddressDetails->city;
            $userCountry  = 'India';
            $userPost     = $userAddressDetails->postal_code; 
            //if (empty($toAddress)) {
              $address  = $userAddress.' '.$userCity. ' '.$userState . ' '.$userCountry.' '.$uuserPost;
            //} else {
             // $address  = $toAddress .' ' . $cityName.' ' . $stateName . ' '.$pinCode;
            //}
            $delivery_address  = $toAddress .' ' . $cityName.' ' . $stateName . ' '.$pinCode;
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
//             if (@getimagesize($usrImage)) {
//               $img  = $usrImage;
//             } else {
//               $img  = $noImage;
//             }

            /*if(!empty($model->image)){
                $requestImg = Yii::app()->request->baseUrl.'/uploads/request/'.$model->image;
            }else{
                $requestImg = Yii::app()->request->baseUrl.'/images/empty-product.png';
            }*/

            
            //$request_url=Yii::app()->request->baseUrl.'/images/no-img.png';
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
            if($model->payment_date=="0000-00-00 00:00:00"){
                $payment_date = "Payment date not available";
            }else{
                $payment_date = Helper::dateFormat($model->payment_date);
            }
            
            if($model->delivery_date=="0000-00-00 00:00:00"){
                $delivery_date = "Delivery date not available";
            }else{
                $delivery_date = Helper::dateFormat($model->delivery_date);
            }

         ?>
          <!-- Profile Image -->
          <div class="box box-primary">
            <div class="box-body box-profile">
 			  <a href="<?php echo $img; ?>" data-fancybox data-caption="Profile">
              		<img class="profile-user-img img-responsive img-circle" src="<?php echo $img; ?>" alt="User profile picture" style="border-radius: 0!important;">
              </a>
              <h3 class="profile-username text-center"><?php echo $userName; ?><br><span style="font-size: 12px;">(<?php echo $usertype;?>)</span></h3>
              <ul class="list-group list-group-unbordered">
              	<li class="list-group-item">
                  <b>Item : </b> <a id="item_text"><?php echo nl2br($itemName); ?></a>     <i class="fa fa-pencil-square-o item_div_close" aria-hidden="true" id="<?php echo $model->id;?>" ref="item_div" onclick="make_editable(this);"  style="cursor:pointer;"></i>   <i class="fa fa-minus-square-o item_div" aria-hidden="true" ref="item_div"  style="cursor:pointer;display:none;" onclick="make_uneditable(this);" ></i>
                  <p>
                  	 <div class="form-group item_div" style="display:none;">
                      	<textarea class="form-control" id="item_name" rows="1" id="comment"><?php echo $itemName;?></textarea>
                      	<button type="button" class="btn btn-success" style="padding: 0px 2px;margin-top: 10px;" id="item_save" onclick="save_content(1,'<?php echo $model->id;?>');">Save</button>
                     </div>
                  </p>
                </li>
                <li class="list-group-item">
                  <b>Additional Information : </b> <a id="info_text"><?php echo ($model->additional_info)? nl2br($model->additional_info):'Not available'; ?></a>     <i class="fa fa-pencil-square-o info_div_close" ref="info_div" aria-hidden="true"  id="<?php echo $model->id;?>" onclick="make_editable(this);" style="cursor:pointer;"></i>   <i class="fa fa-minus-square-o info_div" aria-hidden="true"  ref="info_div" style="cursor:pointer;display:none;" onclick="make_uneditable(this);" ></i>
                  <p>
                  	 <div class="form-group info_div" style="display:none;">
                      	<textarea class="form-control" id="additional_info" rows="1" id="comment"><?php echo $model->additional_info;?></textarea>
                      	<button type="button" class="btn btn-success" style="padding: 0px 2px;margin-top: 10px;" id="additional_save" onclick="save_content(2,'<?php echo $model->id;?>');">Save</button>
                     </div> 
                  </p>
                </li>
                <li class="list-group-item">
                  <b>Vendor Information : </b> <a id="vendor_text"><?php echo ($model->vendor_info)? nl2br($model->vendor_info):'Not available'; ?></a>     <i class="fa fa-pencil-square-o vendor_div_close" ref="vendor_div" aria-hidden="true"  id="<?php echo $model->id;?>" onclick="make_editable(this);" style="cursor:pointer;"></i>   <i class="fa fa-minus-square-o vendor_div" aria-hidden="true"  ref="vendor_div" style="cursor:pointer;display:none;" onclick="make_uneditable(this);" ></i>
                  <p>
                  	 <div class="form-group vendor_div" style="display:none;">
                      	<textarea class="form-control" id="vendor_info" rows="1" id="comment"><?php echo $model->vendor_info;?></textarea>
                      	<button type="button" class="btn btn-success" style="padding: 0px 2px;margin-top: 10px;" id="vendor_save" onclick="save_content(3,'<?php echo $model->id;?>');">Save</button>
                     </div> 
                  </p>
                </li>
                <li class="list-group-item">
                  <b>Booking Date</b> <p><a><?php echo $bookingDate; ?></a></p>
                </li>
                <li class="list-group-item">
                  <b>Payment Date</b> <p><a><?php echo $payment_date; ?></a></p>
                </li>
                <li class="list-group-item">
                  <b>Delivery Date</b> <p><a><?php echo $delivery_date; ?></a></p>
                </li>
                <li class="list-group-item">
                  <b>Booking Code</b> <p><a><?php echo $model->request_code; ?></a></p>
                </li>
                <li class="list-group-item">
                  <b>Request Status</b> 
                  <p>
                  	<?php if($model->status=="Request Placed"){
                  	    echo "<a class='btn btn-success btn-xs' href='#' style='cursor:not-allowed;'>Request Placed</a>";
                  	}else if($model->status=="Waiting for payment"){ 
                  	    echo "<a class='btn btn-danger btn-xs' href='#' style='cursor:not-allowed;background-color: #FF7F50;border-color: #FF7F50;'>Waiting for payment</a>";
                  	}else if($model->status=="Payment in progress"){ 
                  	    echo "<a class='btn btn-info btn-xs' href='#' style='cursor:not-allowed;'>Payment in progress</a>";
                  	}else if($model->status=="Payment completed"){ 
                  	    echo "<a class='btn btn-success btn-xs' href='#' style='cursor:not-allowed;'>Payment completed</a>";
                  	}else if($model->status=="Delivered to dropbox"){
                  	    echo "<a class='btn btn-success btn-xs' href='#' style='cursor:not-allowed;'>Delivered to dropbox</a>";
                  	}else if($model->status=="Received to dropbox"){
                  	    echo "<a class='btn btn-success btn-xs' href='#' style='cursor:not-allowed;'>Received to dropbox</a>";
                  	}else if($model->status=="Delivered" || $model->status=="Delivered to user"){ 
                  	    echo "<a class='btn btn-success btn-xs' href='#' style='cursor:not-allowed;'>Delivered</a>";
                  	}else{
                  	    echo "<a class='btn btn-danger btn-xs' href='#'>Cancelled</a>";
                  	}?>
                  </p>
                </li>
                <?php if($model->status=="Request Placed"){?>
                <li class="list-group-item">
                  	<b>Cancel request</b> <p><a><?php echo $model->CancelRequest($model); ?> </a></p>
                </li>
                <?php }?>
              </ul>
              <div class="box-body box-profile">
              	<b>Item Image</b> <p></p>
                <div class="request-image" style="padding-top:10px;">
                <a href="<?php echo $request_url; ?>" data-fancybox data-caption="Request image">
                  <img class="img-responsive" width="100px" height="100px" src="<?php echo $request_url; ?>" alt="Request image" style="border: 2px solid #A5A5A5;">
                </a> 
                </div>  
                <h5></h5>
            </div>
            </div>
          </div>
          <!-- /.box -->

          <!-- About Me Box -->

          
          <!-- /.box -->
        </div>
        <!-- /.col -->

        <div class="col-md-9">
          <div class="box box-primary">
            <div class="text-center">
              <!-- <i class="fa fa-shopping-basket" aria-hidden="true"></i> -->
              <h2><u>Request Summary</u></h2>
            </div>
            <br>
            <div class="row item-info">
              <div class="row invoice-info">
                <div class="col-sm-4 invoice-col">
                  <h4>Pickup Address</h4>
                  <address class="item-details-text">
                  	<?php $city         = Cities::model()->findByAttributes(array('city_id'=>$model->to_city));?>
                    <?php echo "<b>Name : </b>".$userDetails->first_name." ".$userDetails->last_name."<br/><br/><b>Address : </b>".$model->to_address .' ' . $city->city_name.' <br/>' . $model->to_state . ' '.$model->to_pincode; ?>
                  </address>
                </div>
                <div class="col-sm-4 invoice-col">
                 <h4> Doothan Address</h4>
                  <address class="item-details-text" id="doothan_address" style="padding-top: 12px;">
                  	<?php 
                  	if($model->doothan_id!=0){
                  	    $doothan_Details = UserAddress::model()->findByAttributes(array('user_id'=>$model->doothan_id));
                  	    $doothan_datas = Users::model()->findByPk($model->doothan_id);
                  	    $doothan_address  = "<b>Name : </b>".$doothan_datas->first_name." ".$doothan_datas->last_name."<br/><br/><b>Address : </b>".$doothan_Details->address.' '.$doothan_Details->city. '<br/> '.$doothan_Details->state.' '.$doothan_Details->postal_code;
                  	}else{
                  	    $doothan_address = "Doothan Not Found";
                  	}
                  	?>
                   <?php echo $doothan_address; ?>
                  </address>
                </div>
                <div class="col-sm-4 invoice-col">
                 <h4> Dropbox Address</h4>
                  <address class="item-details-text">
                   <?php 
                  	$dropbox_details = Users::model()->findByPk($model->dropbox_id);
                  	$userAddressDetails  = UserAddress::model()->findByAttributes(array('user_id'=>$model->dropbox_id));
                  	$userAddress  = $userAddressDetails->address;
                  	$userState    = $userAddressDetails->state;
                  	$userCity     = $userAddressDetails->city;
                  	$userCountry  = 'India';
                  	$userPost     = $userAddressDetails->postal_code;
                   ?>
                   <?php echo "<b>Name : </b>".$dropbox_details->first_name." ".$dropbox_details->last_name."<br/><br/><b>Address : </b>".$userAddress.' '.$userCity. '<br/> '.$userState . ' '.$userCountry.' '.$userPost; ?>
                  </address>
                </div>
          	</div>
            <br><br>
            <div class="">
              <h4>Distance Between Pickup Location & Doothan  : <?php echo $model->distance;?>Km</h4>
            </div>
            <br>
            <br>
          	<div class="text-center">
              <h2><u>Payment Details</u></h2>
            </div>
            <br>
            <div class="row item-info">
               	<div class="table-responsive">
                    <table class="table">
                      <tbody>
                      <tr>
                        <th>Minimum Amount</th>
                        <td><i class="fa fa-inr" aria-hidden="true"></i> <?php echo number_format((float)$model->base_amount, 2, '.', ''); ?></td>
                      </tr>
                      <tr>
                        <th>Transportation Charge ( <?php echo $model->rate_per_km; ?> per KM)</th>
                        <td><i class="fa fa-inr" aria-hidden="true"></i> <?php $dcharge=intval($model->distance)*$model->rate_per_km;echo number_format((float)$dcharge, 2, '.', ''); ?></td>
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
                        $bc = $dcharge;
                        $abc = $model->base_amount + $bc - $ab + $model->weight;
                        ?><?php echo number_format((float)$abc, 2, '.', ''); ?></td>
                      </tr>
                      <tr>
                        <th>Tax ( <?php echo $model->gst;?> %)</th>
                        <td><i class="fa fa-inr" aria-hidden="true"></i> <?php echo $gst_amount=$abc/100*$model->gst;number_format((float)$gst_amount, 2, '.', ''); ?></td>
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
                    </tbody>
                 </table>
              	</div>
            </div>	
           </div>
          </div>
        </div>
      </div>
</section> 
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script type="text/javascript">
jQuery(function ($) {
    jQuery(document).on("change", '#pageSize', function () {
        $.fn.yiiGridView.update('customers-grid', {data: {pageSize: $(this).val()}});
    });
});
$("[data-fancybox]").fancybox({
}); 

function Cancel_request(param){
    var id = $(param).attr('id');
    if(confirm("Are you sure to cancel this order ?")==true){
        $(param).html('please wait..');
        $.ajax({
        	type:'POST',
        	dataType:'html',
        	data:{'id':id},
        	url:'<?php echo Yii::app()->createAbsoluteUrl("request/CancelRequest"); ?>',
        	success:function(response){
        		window.location.reload();
        	},error: function(jqXHR, textStatus, errorThrown) {
        		window.location.reload();
            }
        });
    }
}

function make_editable(param){
	var request_id = $(param).attr('id');
	var div = $(param).attr('ref');
	$('.'+div).show();
	$('.'+div+'_close').hide();
	
}

function make_uneditable(param){
	var div = $(param).attr('ref');
	$('.'+div).hide();
	$('.'+div+'_close').show();
}


function save_content(type,id){
	if(type==1){
		value = $('#item_name').val();
	}else if(type==2){
		value = $('#additional_info').val();
	}else{
		value = $('#vendor_info').val();
	}
	$.ajax({
    	type:'POST',
    	dataType:'html',
    	data:{'value':value,'type':type,'request':id},
    	url:'<?php echo Yii::app()->createAbsoluteUrl("request/AddAddtionalInfo"); ?>',
    	success:function(response){
        	if(response=="1"){
        		if(type=="1"){
        			$('#item_text').html(value);
        			$('.item_div').hide();//item_div
        			$('.item_div_close').show();
        		}else if(type=="2"){
        			$('#info_text').html(value);
        			$('.info_div').hide();//item_div
        			$('.info_div_close').show();
        		}else{
        			$('#vendor_text').html(value);
        			$('.vendor_div').hide();//item_div
        			$('.vendor_div_close').show();
        		}
        		swal({
        			  title: "Success!",
        			  text: "Changes updated!",
        			  icon: "success",
        			  button: "Ok!",
        		});
        	}else{
				swal({
      			  title: "Oops...!",
      			  text: "Something went wrong!",
      			  icon: "error",
      			  button: "Ok!",
      			});
            }
    	},error: function(jqXHR, textStatus, errorThrown) {
    		window.location.reload();
        }
    });
}
</script> 

