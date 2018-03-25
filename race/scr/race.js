// JavaScript Document
function RACE(opt){	
    var obj = this;
    this.opt = opt;
    this.interval = {};
    this.params = {try_id:opt.id_user};
    this.shablon = '<div class="racer radius"><div class="ava_racer"><img/></div><div class="name_racer"></div></div>';
    this.vxod = false;
    this.car = false;
    this.countStart = 0;
    this.start = false;
    this.score = 0;
    this.timer_stop;
    this.search_c = false;
    this.timeout = 60;
    this.shtrafTime =10;
    this.shtrafNeed = true;
    
    this.start_search = function(){
        if(!this.search_c)
            this.interval = setInterval(obj.search, 2000);
        this.search_c = true;
    };
    this.stop_search = function(){
        clearInterval(this.interval);
        this.search_c = false;
    };
    this.search = function(){
        obj.getJSON(obj.opt.url_race, obj.params, function(data){
            console.log(data);
            obj.late(data);
            if(obj.params.try_id !== undefined){
                console.log('try continue')
                if(data.id_race !== undefined){
                    console.log('continue')
                    obj.params.id_user = obj.opt.id_user;
                    $('#start_but').remove();
                }
                delete obj.params.try_id;
                return false;
            }
            obj.update_race(data); 
            
        });
    };
    
    this.get_name = function(data){
        if(!data.first_name){
            return data.name;
        }
        return data.first_name;
    };
    
    this.update_race = function(data){
        $('#racers').empty();
        delete this.params.score;
        
        this.check_vxod(data);
        
        if(data.length){
            this.track(data);
            if(data[0].status == 'start' && data[this.num].score > 9){
                window.clearTimeout(obj.timer_stop);
                this.start_search();
                this.info('Ожидаем других игроков.');
                return false;
            }
            if(data[0].status == 'finish'){
                this.finish = true;
                this.kubki(data);
                this.stop_search();
                return false;
            }            
            if(data[0].status == 'start' && !this.start){
                this.timer_start(data);            
            }
            if(data[0].status == 'wait'){
                $('#test').empty();
                if(this.vxod){
                    console.log('wait')
                    this.info('Ожидаем еще игрока.<br>Пригласить друзей'+$('#rbtns').html());
                } 
                this.set_racers(data);  
            }            
        }
        if(data.length<4 && !this.vxod && !this.car){
            var el = $('<div></div>').append('Для начала нажмите<br><br>')
                .append($('<button class="button">Начать</button>').click(function(){
                    obj.choise_car(this);
                    $('#info_race').remove();
                }));
            this.info(el);
        }        
    };
    
    this.kubki = function(data){
        $('#test').empty();
        $('#info_race').remove();
        var el = $('<div id="kubki"></div>')
        for(i=0;i<data.length;i++){
            if(data[i].place == 4) continue;
            var r = $(this.shablon).attr('id', 'place'+(data[i].place));
            r.find('.ava_racer').append('<div class="medal"></div>')
            r.find('img').attr('src', data[i].avatar100);
            r.find('.name_racer').html(this.get_name(data[i]));
            el.append(r);
        }
        $('#racers').append(el);        
    };
    
    this.check_vxod = function(data){
        for(i=0;i<data.length;i++){
            if(data[i].id_user == this.opt.id_user){
                this.vxod = true;
                this.num = i;
            }
        }
    };
    
    this.set_racers = function(data){
        for(i=0;i<data.length;i++){
            var r = $(this.shablon);
            r.find('img').attr('src', data[i].avatar100);
            r.find('.name_racer').html(this.get_name(data[i]));
            $('#racers').append(r);
        }
    };
    
    this.info = function(str){
        $('#info_race').remove();
        var el = $($('#but').html()).attr('id', 'info_race');
        $('#racers').after(el);
        $(el[0]).append(str);
    };
    
    this.timer_start = function(data){
        if(this.finish)
            return false;
        this.score = data[this.num].score;
        var text= [
            'Приготовились...',
            'Приготовились... <span id="timer">3</span>',
            'Приготовились... <span id="timer">2</span>',
            'Приготовились... <span id="timer">1</span>',
            'Поехали!',
            'Поехали!'
        ]
        this.info(text[this.countStart]);
        this.countStart++;
        if(this.countStart == 5){
            this.countStart = 0;
            this.start_race(data);
        }
    };
    
    this.start_race = function(data){
        $('#info_race').remove();
        $('#banner_race, #stat_race').hide();
        this.start = true;
        obj.params.score = data[this.num].score;
        $('#racers').empty();
        //this.opt.test.callback = this.test_right;
        this.opt.test.get_random(obj.test_ans);
        console.log('start')
    }
    
    this.late = function(data){
        if(data.length<2)
            return;
        var cont_stop = 1;
        for(var i=0;data.length>i;i++){
            if(data[i].score == 10){
                cont_stop++;
            }
        }
        console.log('cont_stop',cont_stop);
        if(cont_stop == data.length){
            obj.shtrafNeed = false;
        }
    }
    
    this.test_ans = function(ans){
       
        window.clearTimeout(obj.timer_stop);
        obj.start_search();
        
        obj.timer_stop = setTimeout(function(){
            console.log('timeout');
            $('#test').empty();            
            obj.stop_search();
            obj.info('Вы выбыли из гонки.');
        }, obj.timeout*1000);
        console.log('timer')
        console.log(ans);
        if(ans == 'false' && obj.shtrafNeed){
            obj.shtraf();
            return false;
        }        
        if(ans == 'true'){
            obj.score++;
            obj.params.score = obj.score;
        }
        if(obj.params.score > 9){
            console.log('stop')
            return false;
        }
        else return true;
    };
    
    this.shtraf = function(){        
        var  int = obj.shtrafTime;
        var interval = setInterval(function(){
            obj.info('Вы оштрафованы на '+int);            
            if(int == 0){ 
                window.clearInterval(interval);
                $('#info_race').remove();
                obj.opt.test.get_random(obj.test_ans);
            }
            int--;
        }, 1000);
    };
    
    this.track = function(data){
        $('#ptrack, #htrack').remove();
        var headerHTML = $($('.header_panel').get(0)).clone();
        var panelHTML = $($('.panel_block').get(0)).clone();
        $(panelHTML).attr('id','ptrack').html('<div id="racers_track"></div><div id="track"><div id="road1"></div><div id="road2"></div><div id="road3"></div><div id="road4"></div></div>').prependTo('.panel');
        $(headerHTML).attr('id','htrack').html('Трасса').prependTo('.panel');
        for(i=0;i<data.length; i++){
            //console.log(data[i].score)
            $('#road'+(i+1)).append(
                $('<div class="car"><div class="place">'+data[i].place+'</div><img src="/race/images/cars/car5'+data[i].car+'.png"/></div>')
                .css('margin-top',(400 - (40 * data[i].score))+100+'px')
            );
            $('#racers_track').append('<div class="racer50"><img src="'+data[i].avatar50+'"><br>'+this.get_name(data[i])+'</div>');
        }
    };
    
    this.choise_car = function(el){
        this.car = true;
        $(el).remove();
        $('#cars').fadeIn(500);
        $('#cars div').click(function(){
            $('#cars').fadeOut(500);
            obj.params.car = $(this).attr('id').substring(5);
            obj.params.id_user = obj.opt.id_user;
            console.log(obj.params);
            obj.car = false;
        });
    };
    
    this.getJSON = function(url, params, call){
        console.log(url, params);
        params.r=Math.random();
        if(obj.opt.gost !== undefined)
            params.user_status = 'gost';
       // $.getJSON(url, params, call);
        $.get(url, params, function(d){
            //console.log(d);
            var data = JSON.parse(d, function(k,v){
                if (k == 'score') {
                    //console.log(k,v)
                    return parseInt(v, 10);
                }
                return v;
                
            });
            //console.log(JSON.stringify(data));
            call(data);
        });
    };
    this.start_search();
    //$('h1').click(function(){obj.stop_search();})
}
