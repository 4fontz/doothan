<div class="box-body">
    <div>
    	<div id="target-content" >
            <?php
            $limit = 2;
            $request_details = Request::model()->findByPk($request_id);
            $dropbox_address  = UserAddress::model()->findByAttributes(array('user_id'=>$request_details->dropbox_id));
            $sql = 'SELECT user.id as us_id,user.first_name,user.phone,user.account_status,user.email,user.last_name,user.current_location,address.postal_code,user.current_city,address.city from `users` as user left join `user_address` as address on user.id=address.user_id where user.member_type="doothan" and user.status=2 and user.account_status="APPROVED" and address.district='."'$dropbox_address->district'";
            $list_content=Yii::app()->db->createCommand($sql)->queryAll();  
            ?>
            <table class="table table-bordered table-striped">  
                <thead>  
                    <tr>  
                    <th>Selection Box &nbsp;&nbsp;<input name="doothans" id="checkAll" class="chkNumbers" type="checkbox"></th>  
                    <th>Doothan ID</th>
                    <th>Full Name</th>
                    <th>Phone #</th>
                    <th>Email </th>
                    <th>Distance from dropbox location</th>
                    <th>Distance from Pickup location</th>
                    <th>Approx. Doothan Fee</th>
                    </tr>  
                </thead>  
                <tbody>  
                <?php  
                $settings = Settings::model()->find();
                $dropbox_pin_code = $dropbox_address->postal_code;
                $i=1;
                //$count = 5;
                $total_pages_count = array();
                foreach($list_content as $list_data) {
                        $Doothan_pin_code = ($list_data['current_location']!='')?$list_data['current_location']:$list_data['postal_code'];
                        $Doothan_city = ($list_data['current_city']!='')?$list_data['current_city']:$list_data['city'];
                        $params = array($Doothan_pin_code,$dropbox_pin_code);
                        //echo "<pre>";print_r($params)."<br/>";die;
                        $doothan_dropbox_distance = Helper::getLocationDistance($params);
                        //echo "<pre>";print_r($doothan_dropbox_distance);die;
                        $doothan_pickup_dist_param = array($Doothan_pin_code,$request_details->to_pincode);
                        //secho "<pre>";print_r($doothan_pickup_dist_param);die;
                        //echo "<pre>";print_r($doothan_pickup_dist_param);die;
                        $pickup_doothan_distance = Helper::getLocationDistance_pickup($doothan_pickup_dist_param);
                        //echo $pickup_doothan_distance;die;
                        if(intval($doothan_dropbox_distance)<=intval($settings->minimum_km)){?>
                            <tr>  
                                <td>
                                    <div class="form-group">
                                        <label>
                                          <input name="doothans[]" id="<?=$request_id?>" value=<?=$list_data["us_id"]?> class="chkNumber" type="checkbox" onClick="enable_button();">
                                        </label>
                                    </div>
                                </td>
                                <td><?php echo $list_data["us_id"]; ?></td>  
                                <td><?php echo $list_data["first_name"]." ".$list_data["last_name"]; ?></td>  
                                <td><?php echo $list_data["phone"]; ?></td>  
                                <td><?php echo $list_data["email"]; ?></td>  
                                <td><?php echo $doothan_dropbox_distance."<br/>".$Doothan_pin_code." ".$dropbox_pin_code ?></td>
                                <td><?php echo $pickup_doothan_distance."</br>".$Doothan_pin_code." ".$request_details->to_pincode;?></td> 
                                <td><?php $amount_data = $pickup_doothan_distance*$settings->default_distance_charge+5;echo number_format((float)$amount_data, 2, '.', ''); ?></td>  
                            </tr>  
                        	<?php $i++;
                        }
                    }?>
                </tbody>  
            </table> 
        </div>
        <div align="center">
           <ul class='pagination text-center' id="pagination">
            <?php 
              $total_records = count($total_pages_count);
              $total_pages = ceil($total_records / $limit);
              if($total_pages>1){
                 for($i=1; $i<=$total_pages; $i++){
                     if($i == 1){?>
                        <li class='active'  id="<?php echo $i;?>"><a href='javascript:void(0)'><?php echo $i;?></a></li> 
                     <?php }else{?>
                     	<li id="<?php echo $i;?>"><a href='javascript:void(0)'><?php echo $i;?></a></li>
                     <?php }?> 
                 <?php }
              }?> 
           </ul>  
    	</div>
    	<div style="margin-right: 5%;color:red;display:none;float:right;" id="display_it">
    		<span id="header_msg">Unregistred users :</span>
			<ul style="margin-top: 20px;" id="li_content">
			</ul>
  		</div>
    </div>
</div>
<style>
.pagination{margin: 0;}
</style>
<script>
     jQuery("#yw0 li").on('click',function(e){
   	 request_id = '<?php echo $request_id;?>';
     e.preventDefault();
     jQuery("#target-content").html("<img src='<?php echo Yii::app()->request->baseUrl; ?>/images/loading_second.gif'>").css({'text-align':'center'});
     jQuery("#yw0 li").removeClass('active');
     jQuery(this).addClass('active');
            var pageNum = $("a",this).html();
            $.ajax({
            	type:'POST',
            	dataType:'html',
            	data:{'page':pageNum,'request_id':request_id},
            	url:'<?php echo Yii::app()->createAbsoluteUrl("request/LoadContent"); ?>',
            	success:function(response){
            		$('#target-content').html(response);
            	},error: function(jqXHR, textStatus, errorThrown) {
            		$('#target-content').html("Error while loading doothan list,Try after some time..!");
                }
            });
    });

     function Notify_doothan(){
     	 var chkId = '';
		 $('.chkNumber:checked').each(function() {
		  chkId += $(this).val() + ",";
		 });
		 chkId = chkId.slice(0,-1);
         var id = '<?php echo $request_id;?>';
         if(confirm("Are you sure to notify about this request on available doothans ?")==true){
        	 $('#notify_doothan').html('Please wait..');
             $.ajax({
             	type:'POST',
             	dataType:'json',
             	data:{'id':id,'chkId':chkId},
             	url:'<?php echo Yii::app()->createAbsoluteUrl("request/notifyDoothan"); ?>',
             	success:function(response){
                 	if(response.status=="true"){
                     	if(response.unregistred==0){
                     		$('#notify_doothan').html('Notification Send').attr('disabled',true);
                 			$('.notify_'+id).prop('onclick',null).off('click');
                         }
             		}else{
                 		$('#display_it').show();
                        $("#li_content").html("");
             			$('#header_msg').html(response.messages);
             			var str = response.unregistred;
                    	 var array = str.split(',');
                    	 $.each(array, function(index, value) {
                    	  $("#li_content").append("<li>" + value + "</li>");
                    	 });
                    	 $('#notify_doothan').html('Notify Doothan');
            		     $('html, body').animate({ scrollTop: 0 }, 'slow', function () {
            		     });
                 	}
             	},error: function(jqXHR, textStatus, errorThrown) {
             		//window.location.reload();
                 }
             });
         }
     }

     function enable_button(){
   		if($('input[name="doothans[]"]:checked').length > 0){
 			$('#notify_doothan').show();
   	  	}else{
   	  		$('#notify_doothan').hide();
   	  	 }
   	}

   	$("#checkAll").change(function () {
   	    $("input:checkbox").prop('checked', $(this).prop("checked"));
   	  	enable_button();
   	});
</script>