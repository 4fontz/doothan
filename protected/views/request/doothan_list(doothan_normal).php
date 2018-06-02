<div class="box-body">
    <div>
    	<div id="target-content" >
            <?php
            $limit = 10;
            $sql = 'SELECT * FROM `users` as user left join `user_address` as address on user.id=address.user_id where user.member_type="doothan" and user.status=2 and user.account_status="APPROVED" and user.travel_from_to="Yes" and (user.mode_of_commute="Bike" OR user.mode_of_commute="Car" OR user.mode_of_commute="Bus")Order By Case user.mode_of_commute When "Bike" Then 1 When "Car" Then 2 When "Bus" Then 3 Else 4 End';
            $list=Yii::app()->db->createCommand($sql)->queryAll();    
            $total_records = count($list);  
            $total_pages = ceil($total_records / $limit); 
            $sql_data = 'SELECT * FROM `users` as user left join `user_address` as address on user.id=address.user_id where user.member_type="doothan" and user.status=2 and user.account_status="APPROVED" and user.travel_from_to="Yes" and (user.mode_of_commute="Bike" OR user.mode_of_commute="Car" OR user.mode_of_commute="Bus")Order By Case user.mode_of_commute When "Bike" Then 1 When "Car" Then 2 When "Bus" Then 3 Else 4 End LIMIT 0,10';
            $list_content=Yii::app()->db->createCommand($sql_data)->queryAll(); 
            
            ?>
            <table class="table table-bordered table-striped">  
                <thead>  
                    <tr>  
                    <th>Selection Box &nbsp;&nbsp;<input name="doothans" id="checkAll" class="chkNumbers" type="checkbox"></th>  
                    <th>Doothan ID</th>
                    <th>Full Name</th>
                    <th>Phone #</th>  
                    <th>Regsitered Home Location</th>
                    <th>Distance from Pickup location</th>
                    <!-- <th>Distance from home to drop box location</th> -->
                    <th>Approx. Doothan Fee</th>
                    
                    </tr>  
                </thead>  
                <tbody>  
                <?php  
                $request_details = Request::model()->findByPk($request_id);
                $dropbox_address  = UserAddress::model()->findByAttributes(array('user_id'=>$request_details->dropbox_id));
                $settings = Settings::model()->find();
                foreach($list_content as $list_data) {  
                   // $doothan_address  = UserAddress::model()->findByAttributes(array('user_id'=>$list_data['id']));
                    ?>  
                    <tr>  
                    <td>
                        <div class="form-group">
                            <label>
                              <input name="doothans[]" id="<?=$request_id?>" value=<?=$list_data["id"]?> class="chkNumber" type="checkbox" onClick="enable_button();">
                            </label>
                        </div>
                    </td>
                    <td><?php echo $list_data["id"]; ?></td>  
                    <td><?php echo $list_data["first_name"]." ".$list_data["last_name"]; ?></td>  
                    <td><?php echo $list_data["phone"]; ?></td>  
                    <td><?php echo $doothan_address->city." ".$doothan_address->postal_code; ?></td>
                    <td>
                    	<?php 
                    	$location = $dropbox_address->postal_code;
                    	$second_location = $request_details->to_pincode;
                    	$params = array($location,$second_location);
                    	$distance = Helper::getLocationDistance($params);
                    	$distance = $distance;
                    	echo $distance;
                    	?>
                    </td> 
                    <td><?php echo $distance*$settings->default_distance_charge+5; ?></td>  
                    </tr>  
                <?php  }  ?>
                </tbody>  
            </table> 
        </div>
        <div align="center">
            <ul class='pagination text-center' id="pagination">
            <?php if(!empty($total_pages)):for($i=1; $i<=$total_pages; $i++):  
             if($i == 1):?>
                        <li class='active'  id="<?php echo $i;?>"><a href='javascript:void(0)'><?php echo $i;?></a></li> 
             <?php else:?>
             <li id="<?php echo $i;?>"><a href='javascript:void(0)'><?php echo $i;?></a></li>
             <?php endif;?> 
            <?php endfor;endif;?>  
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
     jQuery("#pagination li").on('click',function(e){
   	 request_id = '<?php echo $request_id;?>';
     e.preventDefault();
     jQuery("#target-content").html("<img src='<?php echo Yii::app()->request->baseUrl; ?>/images/loading_second.gif'>").css({'text-align':'center'});
     jQuery("#pagination li").removeClass('active');
     jQuery(this).addClass('active');
            var pageNum = this.id;
            $.ajax({
            	type:'POST',
            	dataType:'html',
            	data:{'page':pageNum,'request_id':request_id},
            	url:'<?php echo Yii::app()->createAbsoluteUrl("request/LoadContent"); ?>',
            	success:function(response){
            		$('#target-content').html(response);
            	},error: function(jqXHR, textStatus, errorThrown) {
            		$('#target-content').html("Error while loading doothan list");
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
                     	if(response.unregistred==''){
                     		$('#notify_doothan').html('Notification Send').attr('disabled',true);
                 			$('.notify_'+id).prop('onclick',null).off('click');
                         }else{
                             $('#display_it').show();
                             $("#li_content").html("");
                             $('#header_msg').html("Oops, couldn't find below doothans device id, try to login again");
                             var str = response.unregistred;
                         	 var array = str.split(',');
                         	 $.each(array, function(index, value) {
                         	  $("#li_content").append("<li>" + value + "</li>");
                         	 });
                         	 $('#notify_doothan').html('Notify Doothan');
                 		     $('html, body').animate({ scrollTop: 0 }, 'slow', function () {
                 		     });
                         }
             		}else{
                 		$('#display_it').show();
                        $("#li_content").html("");
             			$('#header_msg').html(response.message);
             			$('#notify_doothan').html('Notify Doothan');
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