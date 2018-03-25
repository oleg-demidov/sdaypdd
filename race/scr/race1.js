// JavaScript Document
function RACE(opt){
	
	var obj = this;
	this.opt = opt;
	//var searching = false;
	this.countdown = false;
	this.race = 0;
	
	this.init = function(test){
		this.test = test;
		this.add_race();
		this.search_go();
	}
	this.search_go = function(){
		this.searching = true;
		this.search_race();
	}
	this.search_stop = function(){
		this.searching = false;
		clearTimeout(this.timeout);
	}
	this.search_race = function(){
		//$('#info').append(this.opt['url_search']+ ' ')
		$.getJSON(this.opt['url_search']+'?r='+Math.random(0,10000), this.search_call);
	}
	this.search_call = function(data){
		$('#info').append(data.length+' ');
		if(data.length) obj.add_countdown(data[0]['time']);
		else obj.remove_countdown();
		obj.remove_racers();
		obj.remove_button();
		obj.add_racers(data);
		if(data.length < 4) obj.add_button();
		if(obj.searching)
			obj.timeout = setTimeout( function(){obj.search_race()}, 1000);
	}
	this.add_racers = function(data){
		$('.race').append('<div class="racers"></div>');
		if(data.length){
			obj.race = data[0]['id'];
			this.racers = data;
			for(i=0;i<data.length;i++ ){
				this.add_racer(data[i]);
			}
		}
	}
	this.remove_racers = function(){
		$('.racers').remove();
	}
	this.remove_button = function(){
		$('#begin').remove();
	}
	this.add_button = function(){
		$('.race').append('<input class="button" id="begin" type="button" value="Начать">');
		$('#begin').click(function(){obj.begin()});
	}
	this.add_racer = function(data){
		$('.racers').append('<div class="racer" id="r'+data['id_user']+'"></div>');
		$('.racers').append('<img class="car" id="c'+data['id_user']+'" src="'+this.opt.cars_url[data['car']]+'"/>');
		$('#r'+data['id_user']).append('<img align="center" src="'+data['avatar50']+'"/>');
		$('#r'+data['id_user']).append('<div>'+data['name']+'</div>');
		//$('#r'+data['id_user']).css({"background-color":this.opt.cars_color[data['car']]});
	}
	this.begin = function(){
		this.search_stop();
		$('#begin').remove();
		this.remove_countdown();
		$('.race').append('<div class="choise_car" ></div>');
		$('.choise_car').append('<div>Выберете машину.</div>');
		for(i=0;i<this.opt.cars_url.length;i++){
			$('.choise_car').append('<img class="rotate90" id="car'+i+'" src="'+this.opt.cars_url[i]+'"/>');
			$('#car'+i).click(function(){obj.car_choise($(this).attr('id').substring(3))});
		}
		$('.choise_car').fadeIn(500);
	}
	this.car_choise = function(car){
		$('.choise_car').remove();
		url=this.opt['url_in']+'?id_race='+this.race+'&id_user='+this.opt['id_user']+'&car='+car;
		//$('#info').append(url+ ' ')
		$.get(url, this.in_call);
		
	}
	this.in_call = function(data){
		if(data) {
			obj.race = data;
			obj.wait_go();
		}else obj.search_go();
	}
	this.wait_go = function(){
		this.waiting = true;
		this.wait_race();
	}
	this.wait_race = function(){
		url = obj.opt['url_wait']+'?id='+obj.race+'&id_user='+obj.opt['id_user'];
		//$('#info').append(url)
		$.getJSON(url+'&r='+Math.random(0,10000), obj.wait_call);
	}
	this.wait_call = function(data){
		//$('#info').append(' '+data['racers'].length+' ')
		if(data){
			obj.add_countdown(data['time'], obj.check_racers);
			obj.remove_racers();
			obj.remove_button();
			obj.add_racers(data['racers']);
		}
		if(obj.waiting)
			obj.timeout = setTimeout( obj.wait_race, 1000);
	}
	this.check_racers = function(){
		obj.wait_stop();
		obj.remove_countdown();
		if(obj.racers.length>1) obj.start_countdown();
		else obj.abort_race();
	}
	this.start_countdown = function(){
		//$('#info').append('start ')
		this.wait_stop();
		$('body').css("height", 1500);
		$('html, body').animate({scrollTop:$('.race').offset().top - $(window).scrollTop()}, 1000);
		setTimeout( obj.start_race, 1000);
	}
	this.start_race = function(){
		url = obj.opt['url_start']+'?id_race='+obj.race+'&id_user='+obj.opt['id_user'];
		//$('#info').append(url+' ')
		$.get(url, obj.start_call);
	}
	this.start_call = function(data){
		obj.test.callback = obj.right_ans;
		obj.test.get_random();
	}
	this.right_ans = function(){
		$( "#c"+obj.opt.id_user).animate({ "margin-left": "+=50px" }, "slow" );
	}
	this.abort_race = function(){
		this.remove_countdown();
		alert('Игроков нет.\r\nПопробуйте зайти попозже.');
		$('.racers').empty();
		this.race = 0;
		this.search_go();
	}
	this.add_countdown = function(sec, callback){
		if(this.countdown)
			return;
		$('.race').append('<div class="countdown"></div>');
		this.countdown = $('.countdown').FlipClock(sec, {
			clockFace: 'Counter',
			countdown: true,
			autoStart: true,
			callbacks: {
				stop: function() {callback()}
			}
		});
	}
	this.remove_countdown = function(){
		//if(this.countdown)this.countdown.destroy();
		$('.countdown').remove();
		this.countdown = false;
	}
	this.wait_stop = function(){
		this.waiting = false;
		clearTimeout(this.timeout);
	}
	this.add_race = function(){
		$('#'+this.opt['race']).append('<div class="race"></div>');
	}

}
