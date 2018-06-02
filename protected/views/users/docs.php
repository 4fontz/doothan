<?php
$this->breadcrumbs = array(
    'User docs', 
);
?>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.2.5/jquery.fancybox.css">
<script src="//code.jquery.com/jquery-3.2.1.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.2.5/jquery.fancybox.min.js"></script>
<section class="content">
      <div class="row">
       <div class="row">
        <div class="col-md-6">
          <div class="box box-danger box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">Aadhar</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
              <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-header with-border" style="background-color: #fff;color:#000;padding-top: 5%;">
              <div class="form-group" style="padding-left: 10%;padding-right: 10%;">
            	<div class="row">
					<div class="col-md-4">
						Adhar Number : 
					</div>
					<div class="col-md-8">
						<input id="password-field" type="password" class="form-control" name="password" value="<?php echo $model->aadhar_number;?>" disabled="disabled">
              			<span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password" style="margin-right: 10px;"></span>
					</div>
				</div>	
              </div>
            </div>
            <div class="box-body user-docs">
             <?php 
              $aadhar_url=Yii::app()->request->baseUrl.'/images/no-img.png'; 
              if($model->aadhar){
                if(Yii::app()->params['adharImageBucketUrl'].$model->aadhar){
                  $aadhar_url=Yii::app()->params['adharImageBucketUrl'].$model->aadhar;
                }
                
              }else{
                $aadhar_url=Yii::app()->request->baseUrl.'/images/no-img.png'; 
              }
              ?>
              <a href="<?php echo $aadhar_url; ?>" data-fancybox data-caption="Aadhar">
                <img src="<?php echo $aadhar_url; ?>" alt="" />
              </a>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
        <div class="col-md-6">
          <div class="box box-success box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">Passport ID/ Driving license ID/Other Govt ID</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
              </div>
              <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-header with-border" style="background-color: #fff;color:#000;padding-top: 5%;">
            <div class="form-group" style="padding-left: 10%;padding-right: 10%;">
            	<div class="row">
					<div class="col-md-4">
						Document Number : 
					</div>
					<div class="col-md-8">
						<input id="password-field-2" type="password" class="form-control" name="password" value="<?php echo $model->photo_number;?>" disabled="disabled">
              			<span toggle="#password-field-2" class="fa fa-fw fa-eye field-icon toggle-password2" style="margin-right: 10px;"></span>
					</div>
				</div>	
            </div>
            </div>
            <div class="box-body user-docs">
              <?php 
              $photo_url=Yii::app()->request->baseUrl.'/images/no-img.png'; 
              if($model->photo_id){
                if(Yii::app()->params['photoImageBucketUrl'].$model->photo_id){
                  $photo_url=Yii::app()->params['photoImageBucketUrl'].$model->photo_id;
                }
                
              }else{
                $photo_url=Yii::app()->request->baseUrl.'/images/no-img.png'; 
              }
              ?>
              <a href="<?php echo $photo_url; ?>" data-fancybox data-caption="Photo">
                <img src="<?php echo $photo_url; ?>" alt="" />
              </a>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
        <!-- /.col -->
      </div>
      </div>
</section>
<style>
.field-icon {
  float: right;
  margin-left: -20px;
  margin-top: -25px;
  position: relative;
  z-index: 2;
}

.container{
  padding-top:50px;
  margin: auto;
}
</style>
<script type="text/javascript">
$(".toggle-password").click(function() {
	  $(this).toggleClass("fa-eye fa-eye-slash");
	  var input = $($(this).attr("toggle"));
	  if (input.attr("type") == "password") {
	    input.attr("type", "text");
	    input.attr("disabled", "true");
	  } else {
	    input.attr("type", "password");
	  }
	});
$(".toggle-password2").click(function() {
	  $(this).toggleClass("fa-eye fa-eye-slash");
	  var input = $($(this).attr("toggle"));
	  if (input.attr("type") == "password") {
	    input.attr("type", "text");
	    input.attr("disabled", "true");
	  } else {
	    input.attr("type", "password");
	  }
	});
  $("[data-fancybox]").fancybox({
  });
</script>
   