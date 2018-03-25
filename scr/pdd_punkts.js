// JavaScript Document
/*$('body').ready(function(){
						alert(1)
	$('.ssilka').bind('click', function(e){
		e.preventDefault();
		var show_html = function(data){
			if(!data) return;
			$('.popup').append('<div id="popuptext">'+data+'</div>');
			$('.popup .close_window, .overlay').click(function (){
				$('.popup, .overlay').css({'opacity':'0', 'visibility':'hidden'});
				$('#popuptext').remove();
			});
			$('.popup, .overlay').css({'opacity':'1', 'visibility':'visible'});
		}
		$.get($( this ).attr('href'), show_html);
		//return false;
	});
});*/
$('body').ready(ssilkas);
function ssilkas(){
	$('.ssilka').bind('click', function(e){
		e.preventDefault();
		var show_html = function(data){
			if(!data) return;
			$('.popup').append('<div id="popuptext">'+data+'</div>');
			$('.popup .close_window, .overlay').click(function (){
				$('.popup, .overlay').css({'opacity':'0', 'visibility':'hidden'});
				$('#popuptext').remove();
			});
			$('.popup, .overlay').css({'opacity':'1', 'visibility':'visible'});
		}
		$.get($( this ).attr('media'), show_html);
		return false;
	});
	
}