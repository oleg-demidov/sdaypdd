<h1><? echo $CONT->header;?></h1>
<?php
include('scr/func_obuch.php');
//echo rawurldecode($_GET['result']);
function bilets_html(){
	$data_bil = get_bilets($_SESSION['user']['id'], $_SESSION['user']['type']);
	$sd = sizeof($data_bil);
	$url = 'http://'.$_SERVER['HTTP_HOST'].'/index.php?a=test&comm='.$_SESSION['user']['comm'].'&bilet=';
	//$n=0;
	for($i=0;$sd>$i;$i++){
		$prec1 = round($data_bil[$i]['true']/($data_bil[$i]['coll']/100));
		$prec2 = round($data_bil[$i]['false']/($data_bil[$i]['coll']/100));
		echo'<div class="bilet_div">';
		//if($n>4){echo' style="clear:left;';$n=0;}
		echo'<span class="nobil">Билет №',$data_bil[$i]['bilet'],'</span><br>';
		echo'<span class="ansbil">(Вопросов ',$data_bil[$i]['coll'],')</span><br>';
		echo'<div class="shkala"><div style="width:'.$prec1.'%"></div><div style="width:'.$prec2.'%"></div></div>';
		echo'<span class="right_ans">Пройдено: ',$data_bil[$i]['true'],'</span><br>';
		echo'<span class="false_ans">Ошибок: ',$data_bil[$i]['false'],'</span><br>';
		echo'<span class="no_ans">Без внимания: ',$data_bil[$i]['coll']-($data_bil[$i]['true']+$data_bil[$i]['false']),'</span><br>';
		echo'<a class="button" href="',$url,$data_bil[$i]['bilet'],'&length=',$data_bil[$i]['coll'],'">СТАРТ</a></div>';
		//$n++;
	}
}
function tems_html(){
	$data_bil = get_tems($_SESSION['user']['id'], $_SESSION['user']['type']);
	$sd = sizeof($data_bil);
	$url = 'http://'.$_SERVER['HTTP_HOST'].'/index.php?a=test&comm='.$_SESSION['user']['comm'].'&tema=';
	$n=0;
	echo'<div class="tems_info"><span class="right_ans"> Пройдено</span> <span class="false_ans"> Ошибок</span> <span class="no_ans"> Без внимания</span></div>';
	for($i=0;$sd>$i;$i++){
		$prec1 = round($data_bil[$i]['true']/($data_bil[$i]['coll']/100));
		$prec2 = round($data_bil[$i]['false']/($data_bil[$i]['coll']/100));
		echo'<div class="tems"><a href="',$url,$data_bil[$i]['num'],'&length=',$data_bil[$i]['coll'],'">';
		echo'<div>',$data_bil[$i]['category'],'</div>';
		echo'<div class="tems_nums"><div>',$data_bil[$i]['coll'],'</div>';
		echo'<div class="right_ans">',$data_bil[$i]['true'],'</div> ';
		echo'<div class="false_ans">',$data_bil[$i]['false'],'</div>';
		echo'<div class="no_ans">',$data_bil[$i]['coll']-($data_bil[$i]['false']+$data_bil[$i]['true']),'</div></div>';
		echo'<div class="shkala"><div style="width:'.$prec1.'%"></div><div style="width:'.$prec2.'%"></div></div>';
		//echo'<td><a class="button" href="',$url,$data_bil[$i]['num'],'">Начать тест</a></td>';
		echo'</a></div>';
		$n++;
	}
}
function errors_html(){
	$data_tems = get_errors($_SESSION['user']['id'], $_SESSION['user']['type']);
	$sd = sizeof($data_tems);
	$url = 'http://'.$_SERVER['HTTP_HOST'].'/index.php?a=test&comm='.$_SESSION['user']['comm'].'&errors=1';
	$urlpdd = 'http://'.$_SERVER['HTTP_HOST'].'/index.php?a=pdd&cat=';
	$urlsigns = 'http://'.$_SERVER['HTTP_HOST'].'/index.php?a=signs';
	$n=0; $false = 0;
	echo'<div class="errors"><h3>Темы которые вы плохо усвоили</h3> ';
	for($i=0;$sd>$i;$i++){
		if(!$data_tems[$i]['false'])
			continue;
		if($data_tems[$i]['id_category'] == 36 || $data_tems[$i]['id_category'] == 38)
			echo'<a href="',$urlsigns,'">',$data_tems[$i]['category'];
		else
			echo'<a href="',$urlpdd,$data_tems[$i]['id_category'],'">',$data_tems[$i]['category'];
		echo' (<span class="false_ans">',$data_tems[$i]['false'],'</span>)</a>';
		$n++;$false += $data_tems[$i]['false'];
	}
	
	if($false){
		echo'<div class="false_ans" style="margin:10px;">Всего ошибок: ',$false;
		echo' <a class="button" href="',$url,'&length=',$false,'">Исправить</a></div>';
	}else echo'<div class="right_ans" style="margin:10px;">Вы не допускали или исправили ошибки</div>';
	echo'</div>';
	
}
if(isset($_SESSION['user']['id'])){
	if(isset($_GET['type']) && ($_GET['type'] == 'ab' || $_GET['type'] == 'cd')){
		$_SESSION['user']['type'] = $_GET['type'];
		$bd->sql("UPDATE `users` SET `type`=? WHERE `id`=?", array($_GET['type'],$_SESSION['user']['id']));
	}
	$delay = time() - $_SESSION['user']['first_active'];
	//if($delay > 0)
		include('scr/timer.php');
	$razdel; 
	echo get_menu_obuch($razdel);
	echo'<div id="obuchenie_cont">';
	
	//if($delay > 0){
		if($razdel == 'bilets')
			bilets_html();
		if($razdel == 'tems')
			tems_html();
		if($razdel == 'errors')
			errors_html();
	//}else
	//  echo'<a class="button" id="buy_obuch" href="http://'.$_SERVER['HTTP_HOST'].'/index.php?a=buy">Продлить обучение</a>';
	echo'</div>';
}else{
	$CONT->get_page(3);
	//echo '<h1>',$CONT->data['header'],'</h1>';
	echo'<div id="text">',stripslashes($CONT->data['text']),'</div>';
}
?>

