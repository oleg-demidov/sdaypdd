<h1><? echo $CONT->header;?></h1>
<div id="timer">
<span class="afss_mins_bv">00</span>&nbsp;мин.&nbsp;
<span class="afss_secs_bv">00&nbsp;</span>&nbsp;сек.
</div>
<script type="text/javascript" src="<?php echo 'http://',$_SERVER['HTTP_HOST']?>/tests/scr/test.js" ></script>
<script type="text/javascript" >
$('body').ready(start);
function start(){
	var opt = {
		'element' : 'test',
		'location' : '<?php echo 'http://',$_SERVER['HTTP_HOST']?>',
		'test_scr' : '/tests/scr/test.php',
		'img_dir' : '/tests/images/',
		'type' : 'ab',
		'pause' : 1,
		'comments' : false
	};
	var number = 1;
	var count = 0;
	var errors =0;
	var dones = 0;
	var TEST = new PDDTEST(opt);
	$('.btn_cat').click(function(){
		$('.btn_cat').removeClass('btnchk');
		$(this).addClass('btnchk');
		TEST.opt.type = $(this).attr('value').toLowerCase();
	});
	function anser(ans){
		if(ans == 'false'){
			errors++;
			$('.false_ans').text(errors);			
		}else{
			dones++;
			$('.right_ans').text(dones);
		}
	}
	function randn(mind,maxd){
		return Math.floor(Math.random()*(maxd-mind+1)+mind);
	}
	function stop_test(){
		$('.cats').show();
		$('#test').empty();
		TEST.info_upd();
		TEST.results = [];
		$('.test_now').remove();
		count = 0;
		if(dones>17) $('#test').html('<table align="center"><tr><td>'+$('#info').html()+'</td></tr></table><div class="contr">Поздравляю.<br>Вы успешно сдали экзамен.</div>');
		else $('#test').html('<table align="center"><tr><td>'+$('#info').html()+'</td></tr></table><div class="nocontr">Сожалею.<br>Вы провалили экзамен.</div>');
		$('#info').empty();
	}
	function call_que(ans){
		anser(ans);
		count++;
		if(count == 20){
			stop_test();
			return;
		}
		number = randn(0,800);
		go_test();
	}
	function go_test(){
		TEST.info_upd();
		$('#number').val(number);
		$('#test').empty();
		TEST.get_number(number, call_que);
	}
	$('#beginbtn').click(function(){
		$('.cats').hide();
		start_timer();
		go_test();
	});
	$('#btn_num').click(function(){
		number = $('#number').val();
		go_test();
	});
	
	function parseTime_bv(timestamp){
		if (timestamp < 0) timestamp = 0;
		
	 	var hour = Math.floor(timestamp/60/60);
		var mins = Math.floor((timestamp - hour*60*60)/60);
		var secs = Math.floor(timestamp - hour*60*60 - mins*60); 
	 	
		if(String(mins).length > 1)
			$('span.afss_mins_bv').text(mins);
		else
			$('span.afss_mins_bv').text("0" + mins);
		if(String(secs).length > 1)
			$('span.afss_secs_bv').text(secs);
		else
			$('span.afss_secs_bv').text("0" + secs);
		 
	}
	var remain_bv,inter;
	function start_timer(){
		remain_bv   = 1200;
		inter = setInterval(function(){
			remain_bv--;
			parseTime_bv(remain_bv);
			if(remain_bv <= 0){
				stop_test();
				clearInterval(inter);
			}
		}, 1000);
	}
}	
</script>

<div id="info">
</div>

<div class="cats"><div style="margin-top:0.5em;"> Категория: </div><input type="button" class="button btn_cat btnchk" id="btnab" value="AB"/><input type="button" class="button btn_cat" id="btncd"  value="CD"/><input type="button" class="button" id="beginbtn" value="Начать экзамен"/></div><div id="test">
<div id="text" style="clear:both;"><? echo stripslashes($CONT->data['text']);?></div></div>
