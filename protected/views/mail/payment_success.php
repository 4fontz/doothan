<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title><?php echo $header_subject;?></title>
<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
<style>
body { padding:0; margin:0; text-align:center; background-color:#EEE; font-family: 'Montserrat', sans-serif; font-weight:200 !important;}
table {text-align:center;}
h2 {margin:0 auto 20px auto; width:90%;}
h4 {margin:0 auto 10px auto; width:90%;}
p {margin:0 auto 20px auto; font-size:13px; color:#555;  width:90%; font-weight:normal; line-height:22px;}
.img {width: 100%;}
img {max-width:100%;}

</style>
</head>

<body>



<table align="center" border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" bgcolor="#eaeced">

 <tbody>
<tr><td align="center" valign="bottom" style="height:30px;">
 
 </td>
 </tr>
 
 
 
 
 <tr><td align="center" valign="top">
 <table style="max-width:600px; width:100%;" border="0" cellpadding="0" cellspacing="0">
                
<tbody>





<!-- 1 -->

<tr ><td align="center" valign="bottom" style="height:20px;"></td></tr>
<tr>
    <td>
<table bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0" valign="top" style="overflow:hidden !important; border-radius: 20px 20px 0 0; width:100%;">
<tbody>
    
<tr>
<td style="background-color:#525252; background-image:url(/mail_images/welcome.jpg); background-repeat:no-repeat !important; padding:60px 10px; ">
    
    <a href="http://doothan.in/" style="color: #FFF; text-decoration: none;"><img src="http://18.216.127.175/vendor/dist/img/logo.png" alt="Doothan"></img></a>
<h1 style="color:#CCC; padding:0; margin:25px 0 0 0; font-weight:normal;"><?php echo $header_subject?></h1>
<span style="display:inline-block; margin:25px auto;  width:50%; height:1px; background-color:#CCC;"></span>

</td>
</tr>
<tr><td style="height:40px;"></td></tr>
<tr>
<td>
<p style="text-align:left;">Hi <?php echo $name ?>,</p>

    <p style="text-align:left;"><h2 style="text-align:left;"><?php echo $header_subject;?></h1>
    
    </p>

<p style="text-align:left;">
    Your doothan request <b><?php echo $request_code;?></b> <?php echo $msg_body;?></b><br/><br/>
</p>
<?php 
$settings = Settings::model()->find();
$default_weight_limit=$settings->default_weight_limit;
$default_distance_limit=$settings->default_distance_limit;
$default_weight_limit_charge=$settings->default_weight_limit_charge;
$default_weight_charge=$settings->default_weight_charge;
$default_distance_limit_charge=$settings->default_distance_limit_charge;
$default_distance_charge=$settings->default_distance_charge;
$wcharge=$default_weight_charge;
if(intval($requestDetails->distance) >=0){
    $dcharge=(intval($requestDetails->distance)*$requestDetails->rate_per_km);
}else{
    $dcharge=$default_distance_charge;
}
$charge=$requestDetails->base_amount+$dcharge;
//redeem coupon amount
if($requestDetails->coupon_amount > 0){
    $charge=$charge-intval($requestDetails->coupon_amount);
    
}
$charge=$requestDetails->base_amount;
$de = $requestDetails->coupon_amount+$requestDetails->discount;
$bc = $requestDetails->rate_per_km * $requestDetails->distance;
$abc = $charge+$bc;
$last_total = $abc-$de+$requestDetails->weight;
$vat = ($last_total*$requestDetails->gst)/100;
$after_vat = $last_total+$vat;
$last_last = $after_vat+$requestDetails->product_price;
//$gst_value = ($last_total * $requestDetails->gst)/100;
?>
<p style="text-align: left;"><u>Payment Summary</u></p>
<div class="p-tag-list" style="text-align: left;">
<p><u>Payment Summary</u></p>
<p>Request Code : <?php echo $requestDetails->request_code;?></p>
<p>Request Item : <?php echo $requestDetails->item_details;?></p>
<p>Transportation Charge : <?php echo $dcharge;?></p>
<p>Discount : <?php echo $requestDetails->discount; ?></p>
<p>Coupon Amount : <?php echo $requestDetails->coupon_amount; ?></p>
<p>Weight Charge : <?php echo $requestDetails->weight;?></p>
<p>Service Fee before VAT:<?php echo $last_total; ?></p>
<p>Tax ( <?php echo $requestDetails->gst;?> %) : <?php echo $vat; ?></p>
<p>Service Fee after adding VAT(I) : <?php echo $after_vat; ?></p>
<p>Product Price : <?php echo $requestDetails->product_price; ?></p>
<p>Total: <?php echo number_format($requestDetails->amount,2); ?></p>
</div>
<br />
<br />
<p style="text-align:left;">Thank You</p>
<p style="text-align:left;">Best Regards,<br />
<b>Doothan APP team</b></p>
</td>
</tr>
<tr><td style="height:40px;"></td></tr>
   <tr>
        <td style="height:30px;"></td>
    </tr>
    <tr>
        <td style="background-image:url(/mail_images/footer_line.jpg); background-repeat:repeat-x !important; background-position: 0;">
            <a href="http://doothan.in/" style="color: #585858; text-decoration: none;">
                <h4 >DOOTHAN</h4></a></td>
    </tr>
<tr>
    <td>

<p style="margin:0px 0px 0px 0px; font-size:11px; width:100%;">Copyright © Doothan 2018 . All Rights Reserved.</p>

    </td>
</tr>
<tr><td style="height:40px;"></td></tr>
</tbody>
</table>