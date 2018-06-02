
  

  <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3><?php echo Helper::requestorCount(); ?></h3>

              <p>Requestors</p>
            </div>
            <div class="icon">
              <i class="ion-android-people"></i>
            </div>
            <a href="<?php echo Yii::app()->baseUrl.'/users/index?type=requester'; ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-gray">
            <div class="inner">
              <h3><?php echo Helper::deliveryCount(); ?></h3>

              <p>Deliveries</p>
            </div>
            <div class="icon">
              <i class="ion ion-bag"></i>
            </div>
            <a href="<?php echo Yii::app()->baseUrl . '/request/index?search=Delivered'; ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-blue">
            <div class="inner">
              <h3><?php echo Helper::doothanCount(); ?></h3>

              <p>Doothan</p>
            </div>
            <div class="icon">
              <i class="ion-ios-person"></i>
            </div>
            <a href="<?php echo Yii::app()->baseUrl.'/users/index?type=doothan'; ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <h3><?php echo Helper::dropboxCount(); ?></h3>
              <h2><?php  //echo 'HELLOO : '. Helper::userCntByDates(); ?></h2>
              <p>Dropbox Owners</p>
            </div>
            <div class="icon">
              <i class="ion ion-chatbubbles"></i>
            </div>
            <a href="<?php echo Yii::app()->baseUrl.'/users/index?type=dropbox'; ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
      </div>
      <!-- /.row -->
      <!-- Main row -->
      <div class="row">
        <!-- Left col -->
        <section class="col-lg-7 connectedSortable">
          <!-- Custom tabs (Charts with tabs)-->
          <div class="nav-tabs-custom">
            <!-- Tabs within a box -->
            <ul class="nav nav-tabs pull-right">
              
              <li class="pull-left header"><i class="fa fa-map"></i>Business Location</li>
            </ul>
          </div>
          <div class="box box-solid bg-teal-gradient">
            <div class="box-footer no-border">
              <div class="row">
                <div class="col-xs-12 text-center" style="border-right: 1px solid #f4f4f4">
                  <div class="form-group" style="margin-bottom: 0px;">
                  		<?php $model=new Users();echo CHtml::activeDropDownList($model, 'member_type', array('requester'=>'Requester','doothan'=>'Doothan','dropbox'=>'Dropbox'),array('empty'=>'Select Role','onChange'=>'LoadMap(this)','options' => array('requester'=>array('selected'=>true))));?>
              	  </div>
                </div>
                <!-- ./col -->
              </div>
              
              <!-- /.row -->
            </div>
            <!-- /.box-body -->
            <div class="box-footer no-border" id="display_content">
              <div class="row">
                <!-- ./col -->
                <div class="col-xs-12 text-center" style="height:700px;" id="display_show">
                  <div id="dvMap" style="height: 700px;"></div>
                </div>
                <!-- ./col -->
              </div>
              <!-- /.row -->
            </div>
            <!-- /.box-footer -->
          </div>
          </section>
        <section class="col-lg-5 connectedSortable">
          
          <div class="nav-tabs-custom">
            <!-- Tabs within a box -->
            <ul class="nav nav-tabs pull-right">
              <li class="pull-left header"><i class="fa fa-inbox"></i>Request Status</li>
            </ul>
          </div>
          <div class="box-footer text-black">
              <div class="row">
                <div class="col-sm-6">
                  <!-- Progress bars -->
                  <a href="<?php echo Yii::app()->baseUrl . '/request/index?search=Request Placed'; ?>" style="cursor: pointer;">
                      <div class="clearfix">
                        <span class="pull-left">Request Placed</span>
                        <small class="pull-right"><?php echo Helper::order_status('Request Placed'); ?></small>
                      </div>
                      <div class="progress xs">
                        <div class="progress-bar progress-bar-green" style="width: <?php echo Helper::order_status_percentage('Request Placed'); ?>"></div>
                      </div>
				  </a>	
				  <a href="<?php echo Yii::app()->baseUrl . '/request/index?search=Waiting for payment'; ?>" style="cursor: pointer;">
                      <div class="clearfix">
                        <span class="pull-left">Waiting for payment</span>
                        <small class="pull-right"><?php echo Helper::order_status('Waiting for payment'); ?></small>
                      </div>
                      <div class="progress xs">
                        <div class="progress-bar progress-bar-green" style="width: <?php echo Helper::order_status_percentage('Waiting for payment'); ?>"></div>
                      </div>
                  </a>
                </div>
                <!-- /.col -->
                <div class="col-sm-6">
                <a href="<?php echo Yii::app()->baseUrl . '/request/index?search=Payment in progress'; ?>" style="cursor: pointer;">
    				  <div class="clearfix">
                        <span class="pull-left">Payment in progress</span>
                        <small class="pull-right"><?php echo Helper::order_status('Payment in progress'); ?></small>
                      </div>
                      <div class="progress xs">
                        <div class="progress-bar progress-bar-green" style="width: <?php echo Helper::order_status_percentage('Payment in progress'); ?>"></div>
                      </div>
				</a>
				<a href="<?php echo Yii::app()->baseUrl . '/request/index?search=Payment completed' ?>" style="cursor: pointer;">
                      <div class="clearfix">
                        <span class="pull-left">Payment completed</span>
                        <small class="pull-right"><?php echo Helper::order_status('Payment completed'); ?></small>
                      </div>
                      <div class="progress xs">
                        <div class="progress-bar progress-bar-green" style="width: <?php echo Helper::order_status_percentage('Payment completed'); ?>"></div>
                      </div>
                 </a> 
                </div>
                <div class="col-sm-6">
                <a href="<?php echo Yii::app()->baseUrl . '/request/index?search=Delivered to dropbox'; ?>" style="cursor: pointer;">
    				  <div class="clearfix">
                        <span class="pull-left">Delivered to dropbox</span>
                        <small class="pull-right"><?php echo Helper::order_status('Delivered to dropbox'); ?></small>
                      </div>
                      <div class="progress xs">
                        <div class="progress-bar progress-bar-green" style="width: <?php echo Helper::order_status_percentage('Delivered to dropbox'); ?>"></div>
                      </div>
				</a>
				<a href="<?php echo Yii::app()->baseUrl . '/request/index?search=Received to dropbox' ?>" style="cursor: pointer;">
                      <div class="clearfix">
                        <span class="pull-left">Received to dropbox</span>
                        <small class="pull-right"><?php echo Helper::order_status('Received to dropbox'); ?></small>
                      </div>
                      <div class="progress xs">
                        <div class="progress-bar progress-bar-green" style="width: <?php echo Helper::order_status_percentage('Received to dropbox'); ?>"></div>
                      </div>
                 </a> 
                </div>
                <div class="col-sm-6">
                    <a href="<?php echo Yii::app()->baseUrl . '/request/index?search=Delivered'; ?>" style="cursor: pointer;">
        				  <div class="clearfix">
                            <span class="pull-left">Delivered</span>
                            <small class="pull-right"><?php echo Helper::order_status('Delivered'); ?></small>
                          </div>
                          <div class="progress xs">
                            <div class="progress-bar progress-bar-green" style="width: <?php echo Helper::order_status_percentage('Delivered'); ?>"></div>
                          </div>
    				</a>
    				<a href="<?php echo Yii::app()->baseUrl . '/request/index?search=Delivered to user'; ?>" style="cursor: pointer;">
        				  <div class="clearfix">
                            <span class="pull-left">Delivered to user</span>
                            <small class="pull-right"><?php echo Helper::order_status('Delivered to user'); ?></small>
                          </div>
                          <div class="progress xs">
                            <div class="progress-bar progress-bar-green" style="width: <?php echo Helper::order_status_percentage('Delivered to user'); ?>"></div>
                          </div>
    				</a>
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->
            </div>	<br/>            
          	<div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Doothan Login Status</h3>
                  <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                  </div>
                </div>
                <?php 
                      $loginStatUrl   = Yii::app()->baseUrl . '/users/index?type=doothan&Users[account_status]=call_verification_pending';
                      $docPendurl     = Yii::app()->baseUrl . '/users/index?type=doothan&Users[account_status]=documents_pending';
                      $apprdurl       = Yii::app()->baseUrl . '/users/index?type=doothan&Users[account_status]=approved';
                      $rjctdurl       = Yii::app()->baseUrl . '/users/index?type=doothan&Users[account_status]=rejected';
                ?>
                <div class="box-body">
                  <ul class="products-list product-list-in-box">
                    <li class="item">
                      <div class="product-img">
                        <img src="<?php echo Yii::app()->baseUrl."/images/docs.png";?>" alt="Documents Pending">
                      </div>
                      <div class="product-info">
                        <a href="<?php echo $docPendurl;?>" class="product-title" style="font-weight: 100;">Documents Pending
                        <span class="label label-warning pull-right" style="font-size: 100%;font-weight: 100;"><?php echo Helper::login_status('DOCUMENTS_PENDING','doothan'); ?></span></a>
                      </div>
                    </li>
                    <!-- /.item -->
                    <li class="item">
                      <div class="product-img">
                        <img src="<?php echo Yii::app()->baseUrl."/images/phone.png";?>" alt="Call Verification Pending">
                      </div>
                      <div class="product-info">
                        <a href="<?php echo $loginStatUrl;?>" class="product-title" style="font-weight: 100;">Call Verification Pending
                          <span class="label label-info pull-right" style="font-size: 100%;font-weight: 100;"><?php echo Helper::login_status('CALL_VERIFICATION_PENDING','doothan'); ?></span></a>
                      </div>
                    </li>
                    <!-- /.item -->
                    <li class="item">
                      <div class="product-img">
                        <img src="<?php echo Yii::app()->baseUrl."/images/success-tick.png";?>" alt="Approved">
                      </div>
                      <div class="product-info">
                        <a href="<?php echo $apprdurl;?>" class="product-title" style="font-weight: 100;">Approved 
                        	<span class="label label-danger pull-right" style="font-size: 100%;font-weight: 100;"><?php echo Helper::login_status('APPROVED','doothan'); ?></span></a>
                      </div>
                    </li>
                    <!-- /.item -->
                    <li class="item">
                      <div class="product-img">
                        <img src="<?php echo Yii::app()->baseUrl."/images/close.png";?>" alt="Rejected" style="height:20px;width:20px;">
                      </div>
                      <div class="product-info">
                        <a href="<?php echo $rjctdurl;?>" class="product-title" style="font-weight: 100;">Rejected
                          <span class="label label-success pull-right" style="font-size: 100%;font-weight: 100;"><?php echo Helper::login_status('REJECTED','doothan'); ?></span></a>
                      </div>
                    </li>
                    <!-- /.item -->
                  </ul>
                </div>
          	</div>
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Dropbox Login Status</h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
              </div>
            </div>
            <?php 
              $loginStatUrl   = Yii::app()->baseUrl . '/users/index?type=dropbox&Users[account_status]=call_verification_pending';
              $docPendurl     = Yii::app()->baseUrl . '/users/index?type=dropbox&Users[account_status]=documents_pending';
              $apprdurl       = Yii::app()->baseUrl . '/users/index?type=dropbox&Users[account_status]=approved';
              $rjctdurl       = Yii::app()->baseUrl . '/users/index?type=dropbox&Users[account_status]=rejected';
            ?>
            <div class="box-body">
              <ul class="products-list product-list-in-box">
                <li class="item">
                  <div class="product-img">
                    <img src="<?php echo Yii::app()->baseUrl."/images/docs.png";?>" alt="Documents Pending">
                  </div>
                  <div class="product-info">
                    <a href="<?php echo $docPendurl;?>" class="product-title" style="font-weight: 100;">Documents Pending
                    <span class="label label-warning pull-right" style="font-size: 100%;font-weight: 100;"><?php echo Helper::login_status('DOCUMENTS_PENDING','dropbox'); ?></span></a>
                  </div>
                </li>
                <!-- /.item -->
                <li class="item">
                  <div class="product-img">
                    <img src="<?php echo Yii::app()->baseUrl."/images/phone.png";?>" alt="Call Verification Pending">
                  </div>
                  <div class="product-info">
                    <a href="<?php echo $loginStatUrl;?>" class="product-title" style="font-weight: 100;">Call Verification Pending
                      <span class="label label-info pull-right" style="font-size: 100%;font-weight: 100;"><?php echo Helper::login_status('CALL_VERIFICATION_PENDING','dropbox'); ?></span></a>
                  </div>
                </li>
                <!-- /.item -->
                <li class="item">
                  <div class="product-img">
                    <img src="<?php echo Yii::app()->baseUrl."/images/success-tick.png";?>" alt="Approved">
                  </div>
                  <div class="product-info">
                    <a href="<?php echo $apprdurl;?>" class="product-title" style="font-weight: 100;">Approved 
                    	<span class="label label-danger pull-right" style="font-size: 100%;font-weight: 100;"><?php echo Helper::login_status('APPROVED','dropbox'); ?></span></a>
                  </div>
                </li>
                <!-- /.item -->
                <li class="item">
                  <div class="product-img">
                    <img src="<?php echo Yii::app()->baseUrl."/images/close.png";?>" alt="Rejected" style="height:20px;width:20px;">
                  </div>
                  <div class="product-info">
                    <a href="<?php echo $rjctdurl;?>" class="product-title" style="font-weight: 100;">Rejected
                      <span class="label label-success pull-right" style="font-size: 100%;font-weight: 100;"><?php echo Helper::login_status('REJECTED','dropbox'); ?></span></a>
                  </div>
                </li>
                <!-- /.item -->
              </ul>
            </div>
      	</div>
        </section>
      </div>
      <div class="nav-tabs-custom">
        <ul class="nav nav-tabs pull-right">
          <li class="pull-left header"><i class="fa fa-inbox"></i>Users and documents</li>
        </ul>
      </div>
      <div class="box">
        <?php echo $this->renderPartial('users_docs',array('User_model'=>$User_model));?>
      </div>
      
    </section>   
<style>
.products-list .product-img img{width: 30px;height: 30px;}
</style>
<script src="http://maps.googleapis.com/maps/api/js?key=AIzaSyA7IZt-36CgqSGDFK8pChUdQXFyKIhpMBY" type="text/javascript"></script>
<script type="text/javascript">
//var markers = [{"title":"trissur","lat":"10.5153293","lng":"76.2044683","description":"haiiikadsjsalkjdhakdhakjdhakdhakshdadakdakjdkajdkasjdhksajhdaksjdhaksdjdhkjsadhakjh"},{"title":"Kottayam","lat":"9.591566799999999","lng":"76.52215309999997"},{"title":"Thiruvananthapuram","lat":"8.5241391","lng":"76.93663760000004"}];
var markers = <?php echo json_encode($all_user_address);?>;
window.onload = function () {
var mapOptions = {
center: new google.maps.LatLng(markers[0].lat, markers[0].lng),
zoom: 10,
mapTypeId: google.maps.MapTypeId.ROADMAP
};
var map = new google.maps.Map(document.getElementById("dvMap"), mapOptions);
var infoWindow = new google.maps.InfoWindow();
var lat_lng = new Array();
var latlngbounds = new google.maps.LatLngBounds();
for (i = 0; i < markers.length; i++) {
var data = markers[i]
var myLatlng = new google.maps.LatLng(data.lat, data.lng);
lat_lng.push(myLatlng);
var marker = new google.maps.Marker({
position: myLatlng,
map: map,
title: data.title,
});
latlngbounds.extend(marker.position);
(function (marker, data) {
google.maps.event.addListener(marker, "click", function (e) {
infoWindow.setContent(data.description);
infoWindow.open(map, marker);
});
})(marker, data);
}
map.setCenter(latlngbounds.getCenter());
map.fitBounds(latlngbounds);

}

function LoadMap(param){
	$('#display_content').html('<img src=<?php echo Yii::app()->request->baseUrl; ?>/images/loading.gif>').css({'color':'red'});
	value = $(param).val();
	$.ajax({
		type:'POST',
		dataType:'html',
		data:{'value':value},
		url:'<?php echo Yii::app()->createAbsoluteUrl("dashboard/loadmap"); ?>',
		success:function(response){
			$('#display_content').html(response);
			//window.location.reload();
		},error: function(jqXHR, textStatus, errorThrown) {
			//window.location.reload();
        }
	});
}
</script>