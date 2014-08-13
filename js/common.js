function popup(type, message){
	$('#popup').removeClass('error').removeClass('green').addClass(type).html(message).show();
	setTimeout(function(){$('#popup').hide()}, 1000);
}