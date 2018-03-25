// JavaScript Document
function RACE(opt){
	
	var obj = this;
	this.opt = opt;
	
	
	this.init = function(test){
		this.test = test;
		this.clear_race();
		this.start_search();
	}
	this.clear_race = function(){
		delete this.data;
		delete this.winners;
		delete this.winner;
		this.winners = [];
		this.wins = 0;
		$('#'+this.opt['race']).empty();
		$('#'+this.opt['race']).append('<div class="race"></div>');
		$('.race').append('<div class="racers"></div>');
	}
	this.start_search = function(){
		console.log('start_search')
		this.id_race = 0;
		this.add_button();
		this.search_race();
	}
	this.add_countdown = function(sec, callback){
		if(callback === undefined)
			callback = function(){};
		
		if(this.countdown){
			//this.countdown.setTime(sec);
			//if(this.id_race && sec < 5)
				//sthis.search_stop();
			return;
		}
		console.log('sec = '+sec)
		$('.race').append('<div class="countdown"></div>');
		this.countdown = $('.countdown').FlipClock(sec, {
			clockFace: 'Counter',
			countdown: true,
			autoStart: true,
			callbacks: {
				stop: function() {
					console.log('timer stop');
					callback();
					obj.remove_countdown();
				}
			}
		});
	}	
	this.remove_countdown = function(){
		//this.countdown.stop();
		$('.countdown').remove();
		this.countdown = false;
	}	
	this.search_race = function(id_race){
		if(id_race === undefined)
			id_race = '';
		else{
			id_race = '&id_race=' + id_race;
			this.id_race = id_race;
		}
		this.url = this.opt['url_search'] + '?id_user=' + this.opt['id_user'] + id_race ;
		this.searching = true;
		this.search_cicle();
	}
	this.search_cicle = function(){
		//$('#info').append(' '+obj.url )
		$.getJSON(obj.url, obj.search_call);
	}
	this.search_stop = function(){
		this.searching = false;
		clearTimeout(this.timeout);
	}
	this.search_call = function(data){
		if(obj.searching )
			obj.timeout = setTimeout( obj.search_cicle, 1000 );
		obj.data = data;
		obj.remove_racers();
		console.log('time = '+data['time'])
		if(data['racers'].length){
			if(data['time'] > 0){
				if(obj.id_race){ obj.add_countdown(data['time'], obj.check_race);
				}else obj.add_countdown(data['time']);
			}
			obj.add_racers(data['racers']);
		}
		
	}
	this.add_racers = function(racers){
		
		if(racers.length){
			var backing = false;
			for(var i=0;i<racers.length;i++ ){
				if(racers[i]['id_user'] == this.opt['id_user'] && !obj.id_race)
					backing = true;
				this.add_racer(racers[i]);
				if(obj.id_race && i == 3){
					this.check_race();
					return;
				}
			}
			if(backing){
				this.remove_countdown();
				$('#begin').remove();
				this.search_stop();
				this.search_race(this.data['id_race']);
			}
		}
	}
	this.add_racer = function(data){
		$('.racers').append('<div class="racer" id="r'+data['id_user']+'"></div>');
		$('.racers').append('<img class="car" id="c'+data['id_user']+'" src="'+this.opt.cars_url[data['car']]+'"/>');
		$('#r'+data['id_user']).append('<img align="center" src="'+data['avatar50']+'"/>');
		$('#r'+data['id_user']).append('<div>'+data['name']+'</div>');
	}
	this.remove_racers = function(){
		$('.racers').empty();
	}
	this.add_button = function(){
		if(obj.opt['id_user'] === undefined)
			return;
		$('.race').append('<input class="button" id="begin" type="button" value="Начать">');
		$('#begin').click(function(){obj.car_choise()});
	}
	this.car_choise = function(){
		$('html, body').animate({scrollTop:0}, 500);
		$('#begin').remove();
		this.remove_countdown();
		//this.search_stop();
		$('.race').append('<div class="choise_car" ></div>');
		//$('.choise_car').append('<div>Выберете машину.</div>');
		for(var i=0;i<this.opt.cars_url.length;i++){
			$('.choise_car').append('<img class="rotate90" id="car'+i+'" src="'+this.opt.cars_url[i]+'"/>');
			$('#car'+i).click(function(){obj.inner_racer($(this).attr('id').substring(3))});
		}
		$('.choise_car').fadeIn(500);
	}
	this.inner_racer = function(car){
		$('.choise_car').remove();
		this.remove_countdown();
		this.search_stop();
		console.log(this.data['id_race'])
		url=this.opt['url_in']+'?id_race='+String(this.data['id_race'])+'&id_user='+this.opt['id_user']+'&car='+car;
		$.getJSON(url, this.wait_racers);
	}
	this.wait_racers = function(data){
		if(data.id_race) obj.search_race(data.id_race);
		else obj.search_race();
	}
	this.check_race = function(){
		console.log('check_race');
		obj.search_stop();
		if(obj.data.racers.length < 2){
			alert('Игроков нет.\r\nПопробуйте зайти попозже.');
			obj.clear_race();
			obj.start_search;
			return;
		}
		//$('#info').append(' start ' )
		obj.start_race(obj.data);
	}
	this.start_race = function(data){
		console.log('start race');
		this.racers = data.racers;
		$('body').css("height", 1500);
		$('html, body').animate({scrollTop:$('#test').offset().top - $(window).scrollTop()}, 500);
		var url = this.opt['url_start']+'?id_race='+this.id_race+'&id_user='+this.opt['id_user'];
		$.get(url, obj.get_que);
	}
	this.get_que = function(data){
		
		obj.test.get_random(obj.ans);
	}
	this.ans = function(ans){
		
		var str = '';
		if(ans !== undefined)
			str = '&id_user='+obj.opt['id_user']+'&ans='+ans;
		var url = obj.opt['url_ans']+'?id_race='+obj.id_race+str;
		$.getJSON(url, obj.racers_upd);
	}
	this.racers_upd = function(data){
		var margin;
		var winners = [];
		var stoper = false;
		for(var i=0; i < data.length; i++){
			if(data[i].result >9){
				if(data[i].id_user == obj.opt['id_user']) stoper = true;
				if(obj.racers[data[i].num-1].position === undefined)
					winners[winners.length] = data[i];
			}
			obj.car_move(data[i]);
		}
		var outers = obj.check_out_racers(data);
		
		winners = obj.sort_winners(winners);
		obj.fix_winners(winners);
		//console.log(obj.racers);
		if(!stoper)
			obj.timeout = setTimeout( obj.get_que, 500);
		else{
			obj.timeout = setTimeout( obj.ans, 1000);
		}
		if(!obj.check_continue(outers)){
			obj.test.opt.element.empty();
			obj.test.opt.element.css("height", "auto");
			obj.wins_last_racers();
			clearTimeout(obj.timeout);
			obj.stop_race();
		}
	}
	this.wins_last_racers =function(){
		console.log(this.racers);
		for(var i=0; this.racers.length > i; i++){
			if(this.racers[i].position === undefined)
				if(!this.racers[i].out)
					if(this.wins)
						this.fix_winners([this.racers[i]]);
		}
	}
	this.check_continue =function(outers){
		var racers = this.racers.length - outers - this.wins;
		if(racers < 2)
			return false;
		else return true;
	}
	this.car_move = function(racer){
		this.racers[racer['num']-1].result = racer.result;
		margin = racer.result*6+"%";
		$("#c"+racer.id_user).animate({ "margin-left": margin }, "slow" );
	}
	this.check_out_racers = function(racers){
		var nul = true;
		var outer = 0;
		for(var i = 0; this.racers.length > i; i++){
			if(this.racers[i].position === undefined){
				for(var r = 0; racers.length > r; r++){
					if(this.racers[i].id_user == racers[r].id_user){
						nul = false;
						if(racers[r].timer < -60){
							this.racer_out(i);
							outer++;
						}else{
							this.racer_come(i);
						}
					}
				}
				if(nul){
					outer++;
					this.racer_out(i);
				}
				nul = true;
			}
		}
		return outer;
	}
	this.racer_out = function(nr){
		if(this.racers[nr].out === undefined && !this.racers[nr].out)
			return;
		this.racers[nr].out = true;
		$('#c'+this.racers[nr].id_user).attr('src', this.opt.url_vibil);
	}
	this.racer_come = function(nr){
		if(this.racers[nr].out === undefined && this.racers[nr].out)
			return;
		this.racers[nr].out = false;
		$('#c'+this.racers[nr].id_user).attr('src', this.opt.cars_url[this.racers[nr]['car']]);
	}
	this.sort_winners = function(winners){
		if(!winners.length)
			return winners;
		function sortNumber(a, b){
			return a - b;
		}
		var times = [];
		var time_winners = [];
		for(var r = 0; r < winners.length; r++){
			times[r] = winners[r].time;
			time_winners[winners[r].time] = winners[r];
		}
		times.sort(sortNumber);
		var sort_winners = [];
		for(r = 0; r < winners.length; r++){
			sort_winners[r] = time_winners[times[r]];
		}
		return sort_winners;
	}
	this.fix_winners = function(winners){
		for(var w = 0; winners.length > w; w++){
			this.racers[winners[w].num-1].position = 1 + this.wins;
			this.wins++;
			this.show_position(this.racers[winners[w].num-1]);
			if(this.racers[winners[w].num-1].position == 1)
				this.winner = this.racers[winners[w].num-1].id_user;
		}
	}
	this.show_position = function(winner){
		$("#r"+winner.id_user).append('<div class="win_sign" id="sign'+winner.position+'"></div>');
	}
	this.stop_race = function(){
		if(this.winner !== undefined){
			if(this.winner == this.opt['id_user'])
				this.winnner(this.winner);
			this.add_countdown(15, function(){obj.clear_race();obj.start_search();});
		}else{
			alert('Игроки выбыли. Попробуйте позже');
			this.clear_race();
			this.start_search();
		}
	}
	this.winnner = function(winner){
		var url = this.opt['url_win'] + '?id_winner=' + winner;
		$.get(url, function(data){});
	}
}
