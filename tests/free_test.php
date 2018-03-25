<h1><? echo $CONT->header;?></h1>
<script type="text/javascript" src="<?php echo 'http://',$_SERVER['HTTP_HOST']?>/scr/pdd_punkts.js" ></script>
<div class="overlay" title="Закрыть"></div>
<div class="popup">
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
	<?	
		if(isset($_GET['comm']))echo"'comments' : ",$_GET['comm'],",";
		if(isset($_SESSION['user']['type']))echo"'type' : '",$_SESSION['user']['type'],"',";
		
	?>
		'pause' : 1
	};
	var rand = false;
	var number = 1;
	var errors =0;
	var dones = 0;
	var TEST = new PDDTEST(opt);
	$('.btn_cat').click(function(){
		$('.btn_cat').removeClass('btnchk');
		$(this).addClass('btnchk');
		TEST.opt.type = $(this).attr('value').toLowerCase();
	});
	$('#btn_rnd, #btn_podsk').click(function(){
		if($(this).hasClass('btnchk')){
			$(this).removeClass('btnchk');
			if($(this).attr('id') == 'btn_rnd') rand = false;
			if($(this).attr('id') == 'btn_podsk') {opt.comments = false; TEST.opt.comments = false; }
			return;
		}
		$(this).addClass('btnchk');
		if($(this).attr('id') == 'btn_rnd') rand = true;
		if($(this).attr('id') == 'btn_podsk') { opt.comments = true; TEST.opt.comments = true;}
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
	function call_que(ans){
		anser(ans);
		if(rand) number = randn(0,800);
		else number++;
		go_test();
	}
	function go_test(){
		$('#number').val(number);
		$('#test').empty();
		TEST.get_number(number, call_que);
	}
	$('#beginbtn').click(function(){
		$(this).remove();
		go_test();
	});
	$('#btn_num').click(function(){
		number = $('#number').val();
		go_test();
	});
}	
</script>
<div class="test_panel">
 <div>Номер: </div>
 <div><input type="text" value="1" size="3" id="number" />
 <input type="button" class="button" id="btn_num" value="Перейти"/></div>
 <div><input type="button" class="button btn_cat btnchk" id="btnab" value="AB"/>
 <input type="button" class="button btn_cat" id="btncd"  value="CD"/></div>
 <div><input type="button" class="button" id="btn_rnd" value="Случайно"/></div>
 <div><input type="button" class="button" id="btn_podsk" value="Подсказки"/></div>
 <div class="right_ans">0</div>
 <div class="false_ans">0</div>
</div>
<div id="test"><div id="text"><? echo stripslashes($CONT->data['text']);?></div><a href="#" class="button" id="beginbtn">Начать тест</a><div id="text"><? echo stripslashes($CONT->data['seotext']);?></div></div>
<?php include 'g_elements/adsblock720_90.php';?>
