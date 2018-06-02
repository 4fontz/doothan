/*
 * Common functions include here
 */
$(function () {
  'use strict'
})

function ChangePagecount(param,controller,action,variable){
	$('.custom_div_data').html("<img src='/doothan/images/loading.gif'>");
	pageCount = $(param).val();
	//alert(pageCount);return false;
	$.ajax({
		type:'POST',
		dataType:'html',
		data:{'pageCount':pageCount,'userType':variable,'flag':'flag'},
		url:Baseurl+'/'+controller+'/'+action,
		success:function(response){
			$('.custom_div_data').html(response);
		},error: function(jqXHR, textStatus, errorThrown) {
			window.location.reload();
        }
	});
}
function ChangePagecount_user(param,controller,action,variable){
	$('.custom_div_data').html("<img src='/doothan/images/loading.gif'>");
	pageCount = $(param).val();
	//alert(pageCount);return false;
	$.ajax({
		type:'POST',
		dataType:'html',
		data:{'pageCount':pageCount,'userType':variable,'flag':'flag'},
		url:Baseurl+'/'+controller+'/'+action+'?type='+variable,
		success:function(response){
			$('.custom_div_data').html(response);
		},error: function(jqXHR, textStatus, errorThrown) {
			window.location.reload();
        }
	});
}