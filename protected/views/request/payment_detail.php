<?php     
$this->breadcrumbs = array(
    'Payment Detail',
);

$OrderDetails = Request::model()->findByPk($model->booking_id);
$UsersDetails = Users::model()->findByPk($OrderDetails->user_id);
$wcharge=$default_weight_charge;
if(intval($OrderDetails->distance) >=0){
    $dcharge=(intval($OrderDetails->distance)*$OrderDetails->rate_per_km);
}else{
    $dcharge=$default_distance_charge;
}
$charge=$OrderDetails->base_amount+$dcharge;
//redeem coupon amount
if($OrderDetails->coupon_amount > 0){
    $charge=$charge-intval($OrderDetails->coupon_amount);
    
}
//discount calculation
if($OrderDetails->discount > 0){
    $charge=$charge-intval($OrderDetails->discount);
}
$charge=$charge+$OrderDetails->weight;
if($OrderDetails->gst > 0){
    $gst_amount=$charge/100*$OrderDetails->gst;
}
$feeGst=$charge+$gst_amount;
if($OrderDetails->distance==0){
    $distance=Helper::getLocationDistance(array($fareFrom,$fareTo));
}else{
    $distance=$OrderDetails->distance;
}
?>
    <!-- Main content -->
    <section class="invoice">
      <!-- title row -->
      <div class="row">
        <div class="col-xs-12">
          <h2 class="page-header">
            <i class="fa fa-globe"></i> <?php echo nl2br($OrderDetails->item_details);?>
            <small class="pull-right">Date: <?php echo $model->userJoinedDate($model)?></small>
          </h2>
        </div>
        <!-- /.col -->
      </div>
      <!-- info row -->
      <div class="row invoice-info">
        <div class="col-sm-4 invoice-col">
          <strong>From</strong>
          <address>
          	<?php if($OrderDetails->vendor_info==NULL){?>
                <strong>Rajeevan Valappil</strong><br>
                795 Folsom Ave, Suite 600<br>
                San Francisco, CA 94107<br>
                Phone: (+1) 8575409198<br>
                Email: rajeevan@gmail.com
            <?php }else{
                echo wordwrap($OrderDetails->vendor_info, 30, "\n",true);
            }?>
          </address>
        </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">
          <strong>To</strong>
          <address>
            <?php echo $UsersDetails->first_name." ".($UsersDetails->last_name)?$UsersDetails->last_name:'';?><br>
            <?php echo ($UsersDetails->user_address)?$UsersDetails->user_address->address:'';?><br>
            <?php echo ($UsersDetails->user_address)?$UsersDetails->user_address->city:''." ".($UsersDetails->user_address)?$UsersDetails->user_address->state:''." ".($UsersDetails->user_address)?$UsersDetails->user_address->postal_code:''?><br>
            Phone: (<?php echo $UsersDetails->country_code;?>) <?php echo $UsersDetails->phone;?><br>
            Email: <?php echo $UsersDetails->email;?>
          </address>
        </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">
          <b>Invoice # : </b><?php echo $model->transaction_id;?><br>
          <br>
          <b>Order ID : </b> <?php echo $OrderDetails->id;?><br>
          <b>Order Code : </b> <?php echo $OrderDetails->request_code; ?><br>
          <b>Payment Status : </b><span style="color:green;font-weight:bold;"> <?php echo $model->status; ?></span>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <!-- Table row -->
      <div class="row">
        <div class="col-xs-12 table-responsive">
          <table class="table table-striped">
            <thead>
            <tr>
              <th>Qty</th>
              <th>Product</th>
              <th>Weight</th>
              <th>Weight Unit</th>
              <th>Payment Mode</th>
              <th>Bank Code</th>
              <th>Subtotal</th>
            </tr>
            </thead>
            <tbody>
            <tr>
              <td>1</td>
              <td><?php echo nl2br($OrderDetails->item_details);?></td>
              <td><?php echo $OrderDetails->weight;?></td>
              <td><?php echo $OrderDetails->weight_unit;?></td>
              <td><?php echo $model->mode;?></td>
              <td><?php echo $model->bankcode;?></td>
              <td><i class="fa fa-inr" aria-hidden="true"></i> <?php echo $model->amount;?></td>
            </tr>
            </tbody>
          </table>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <div class="row">
        <!-- accepted payments column -->
        <div class="col-xs-6">
          <!-- Empty content -->
        </div>
        <!-- /.col -->
        <div class="col-xs-6">
          <div class="table-responsive">
                <table class="table">
                  <tbody>
                  <tr>
                    <th>Minimum Amount</th>
                    <td><i class="fa fa-inr" aria-hidden="true"></i> <?php echo number_format((float)$OrderDetails->base_amount, 2, '.', ''); ?></td>
                  </tr>
                  <tr>
                    <th>Transportation Charge ( <?php echo $OrderDetails->rate_per_km; ?> per KM)</th>
                    <td><i class="fa fa-inr" aria-hidden="true"></i> <?php echo number_format((float)$dcharge, 2, '.', ''); ?></td>
                  </tr>
                  <tr>
                    <th>Discount</th>
                    <td><i class="fa fa-inr" aria-hidden="true"></i> <?php echo number_format((float)$OrderDetails->discount, 2, '.', ''); ?></td>
                  </tr>
                  <tr>
                    <th>Coupon Amount</th>
                    <td><i class="fa fa-inr" aria-hidden="true"></i> <?php echo number_format((float)$OrderDetails->coupon_amount, 2, '.', ''); ?></td>
                  </tr>
                  <tr>
                    <th>Weight Charge</th>
                    <td><i class="fa fa-inr" aria-hidden="true"></i> <?php echo number_format((float)$OrderDetails->weight, 2, '.', ''); ?></td>
                  </tr>
                 
                   <tr>
                    <th> Service Fee before VAT </th>
                    <td><i class="fa fa-inr" aria-hidden="true"></i> <?php 
                    //$ab =  $model->base_amount+$dcharge-($model->discount+$model->coupon_amount)+$wcharge;
                    $ab = $OrderDetails->discount+$OrderDetails->coupon_amount;
                    $bc = $dcharge;
                    $abc = $OrderDetails->base_amount + $bc - $ab + $OrderDetails->weight;
                    ?><?php echo number_format((float)$abc, 2, '.', ''); ?></td>
                  </tr>
                  <tr>
                    <th>Tax ( <?php echo $settings->gst;?> %)</th>
                    <td><i class="fa fa-inr" aria-hidden="true"></i> <?php echo number_format((float)$gst_amount, 2, '.', ''); ?></td>
                  </tr>
                  <tr>
                    <th> Service Fee after adding VAT(I)</th>
                    <td><i class="fa fa-inr" aria-hidden="true"></i> <?php $gst_amount_after = $gst_amount+$abc;echo number_format((float)$gst_amount_after, 2, '.', ''); ?></td>
                  </tr>
                 <tr>
                    <th> Product Price</th>
                    <td><b><i class="fa fa-inr" aria-hidden="true"></i> <?php echo number_format((float)$OrderDetails->product_price, 2, '.', ''); ?></b></td>
                  </tr>
                  
                  <tr>
                    <th>Total:</th>
                    <td> &#x20b9 <b><?php echo number_format((float)$gst_amount_after+$OrderDetails->product_price, 2, '.', ''); ?></b></td>
                  </tr>
                </tbody></table>
              </div>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <!-- this row will not appear when printing 
      <div class="row no-print">
        <div class="col-xs-12">
          <a href="invoice-print.html" target="_blank" class="btn btn-default"><i class="fa fa-print"></i> Print</a>
          <button type="button" class="btn btn-success pull-right"><i class="fa fa-credit-card"></i> Submit Payment
          </button>
          <button type="button" class="btn btn-primary pull-right" style="margin-right: 5px;">
            <i class="fa fa-download"></i> Generate PDF
          </button>
        </div>
      </div>-->
    </section>
    <!-- /.content -->
    <div class="clearfix"></div>
<style>
.invoice{    margin: 5px 15px!important;}
</style>    