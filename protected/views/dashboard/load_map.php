<div class="row">
    <div class="col-xs-12 text-center" style="height:500px;" id="display_show">
      <div id="dvMap" style="height: 500px;"></div>
    </div>
</div>
<script type="text/javascript">
//var markers = [{"title":"trissur","lat":"10.5153293","lng":"76.2044683","description":"haiiikadsjsalkjdhakdhakjdhakdhakshdadakdakjdkajdkasjdhksajhdaksjdhaksdjdhkjsadhakjh"},{"title":"Kottayam","lat":"9.591566799999999","lng":"76.52215309999997"},{"title":"Thiruvananthapuram","lat":"8.5241391","lng":"76.93663760000004"}];
var markers = <?php echo json_encode($all_user_address);?>;

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



</script>