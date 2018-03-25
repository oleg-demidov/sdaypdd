// JavaScript Document
function PDDTEST(opt){
	var obj = this;
	this.opt = opt;
	this.opt['element'] = $('#'+this.opt['element']);
	this.results = [];
	//this.results[this.opt['length']]='';
        
	this.get_number = function(num, callback){
		this.callback = callback;
		this.get_que(('number='+num+'&num=1&type='+this.opt.type));
	}
	this.get_bilet = function(bilet){
                var pars = 'bilet='+bilet+'&ustat='+this.opt.user_id+'&type='+this.opt.type;
                this.get_stat(pars);
		this.num = 0;
		this.razdel = 'bilets';
		this.now_test = 'bilet='+bilet+'&num=';
		this.next_que();
	}
	this.get_random = function(callback){
		this.callback = callback;
		this.num = 0;
		console.log('random');
		this.now_test = 'random=1&num=';
		this.next_que();
	}
	this.get_tema = function(tema){
                var pars = 'tema='+tema+'&ustat='+this.opt.user_id+'&type='+this.opt.type;
                this.get_stat(pars);
		this.num = 0;
		this.razdel = 'tems';
		this.now_test = 'tema='+tema+'&num=';
		this.next_que();
	}
	this.get_false = function(){
		this.num = 0;
		this.razdel = 'errors';
		this.now_test = 'false=1&id_user='+this.opt.user_id+'&num=';
		this.next_que();
	}
	this.get_que = function(get){
		var url = this.opt.location+this.opt.test_scr+'?'+get;
		console.log(url);
		$.getJSON(url, this.que_callback);
	}
        this.get_stat = function(pars){
            var url = this.opt.location+this.opt.test_scr+'?'+pars;
            $.getJSON(url, function(res){
                //console.log(res);
                obj.results = res;
                obj.info_upd();
            });
        }
	this.que_callback = function(data){
		console.log(data);
		if(!data){
			obj.stop_test();
		}else{
			//obj.opt.element.css("height", "auto")
			obj.data = data;
			//if(data.img)
				obj.add_img(data.img);
			obj.add_qtext(data.value);
			obj.add_ans(data);
			//obj.opt.element.css("height", obj.opt.element.height())
		}
	}
	this.info_upd = function(){
		$('.info').empty();
		$('.info').append('<div id="first" class="test_now">Вопрос: </div>')
		var i=0;
                for(i;this.opt.length>i;i++){
                    $('.info').append('<div class="test_next">'+(i+1)+'</div>');
                }
                i=0;
                $('.info div:not(#first)').map(function(){
                    i++;
                    if(obj.num == i){
                        $(this).addClass('test_now');
                        return false;
                    } 
                    if(obj.results[i-1] !== undefined){
                        $(this).addClass('test_'+obj.results[i-1]);
                        return false;
                    }                                
                });
                $('.info div:not(#first)').click(function(){
                    obj.num = $(this).html()-1;
                    obj.next_que();
                    obj.info_upd();
                });
	}
	this.stop_test = function(){
		this.opt.element.html('');
		this.opt.element.append('<a class="button" id="stop_test" href="#">Завершить</a>');
		if(obj.opt.loc_back === undefined)
			url = obj.opt.location+'/index.php?a=free_test';
		else url = obj.opt.location+obj.opt.loc_back;
		$('#stop_test').bind('click', function(){document.location.href = url})
	}
	this.next_que = function(ans){
                if(ans === undefined)
                    ans ='true';
                this.opt.element.html('');
		
                if(this.razdel=='errors'){
                    if(ans=='true'){
                        this.num++;
                    }
                }else
                    this.num++;
                console.log(this.num)
		this.get_que((this.now_test+this.num+'&type='+this.opt.type));
		this.info_upd();
	}
	this.add_qtext = function(text){
		this.opt.element.append('<div class="text_que">'+text+'</div>');
	}
	this.add_img = function(src){
		if(src) var url = this.opt.location+this.opt.img_dir+src+'.jpg';
		else var url = this.opt.location+this.opt.img_dir+'def.jpg';
		this.opt.element.append('<img class="img_test" src="'+url+'"/>');
	}
	this.add_ans = function(data){
                var rr='';
		for(i=0;i<7;i++){
                    rr='';
                    if(data['ans'+i] !== undefined){
                            if(data.right == i) rr = '';
                            this.opt.element.append('<div id="ans'+i+'" class="text_ans">'+rr+i+'. '+data['ans'+i]+'</div>');
                            $('#ans'+i).bind("click", this.click_ans);
                    }
		}
	}
	this.click_ans = function(e){
		$('.text_ans').unbind( "click" );
		var ans = '';
		if(e.target.id.substr(3) == obj.data.right){
			$(e.target).addClass('ans_right');
			ans = 'true';
		}else{
			$(e.target).addClass('ans_error');
			ans = 'false';
		}
		obj.ans(ans);
	}
	this.update_results = function(){}
	this.ans = function(ans){
		if(this.callback !== undefined){
                    if(!this.callback(ans)){
                        return;
                    }
		}
		if(this.opt.userid === undefined){
			this.results[this.num-1] = ans;
			this.update_results();
		}
		if(this.opt.ans_scr === undefined && this.opt.user_id === undefined)
			this.ans_callback(ans);
		else{
			url = (this.opt.location+this.opt.ans_scr+'?id='+obj.data.id+'&id_user='+this.opt.user_id+'&ans='+ans+'&razdel='+this.razdel);
			$.getJSON(url, obj.ans_callback);
		}
		
	}
	this.ans_callback = function(data){
		if(!obj.opt.comments || obj.opt.comments === undefined || data == 'true')
			obj.pause_next_que(data);
		else{
			//obj.opt.element.css("height", "auto");
			obj.opt.element.append('<div class="comment">'+obj.data.comm+'</div>');
			ssilkas();
			obj.opt.element.append('<a class="button" id="next_btn" >Далее</div>');
			$('#next_btn').bind("click", function(){obj.next_que(data)});
		}
	}
	this.pause_next_que = function(data){
		if(obj.opt.pause === undefined)
			obj.opt.pause = 3;
		window.setTimeout(function(){obj.next_que(data)}, (obj.opt.pause*1000));
	}
	
	
}
