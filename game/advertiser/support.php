<h3><? echo content('support')?></h3>
<?
function add_post($id_que,$text){
	global $bd;
	global $lang;
	$data=array(
		'text'=>$text,
		'id'=>rand(0,9999999),
		'time'=>time(),
		'id_user'=>$_SESSION['id'],
		'id_que'=>$id_que
	);
	$admin_id=$bd->select('*','variables',array('name'=>'admin_id'));
	$user_admin=$bd->select('*','users',array('id'=>$admin_id[0]['value']));
	include('../scripts/email_func.php');
	$content_sup=get_cont($lang,'support');
	$mess="Вопрос в службу поддержки<br>".$text;
	$subj='Cs-money.net '.content('support');
	send_mail($user_admin[0]['email'], $subj, $mess);
	return $bd->insert('support_ans',$data);
}

if(isset($_POST['type'])){
	$Qdata=array(
		'type'=>$_POST['type'],
		'id'=>rand(0,9999999),
		'action'=>'on',
		'time'=>time(),
		'id_user'=>$_SESSION['id']
	);
	$bd->insert('support_que',$Qdata);
	$rez=add_post($Qdata['id'],$_POST['text']);
	if($rez){
		$suc=content('request_created');
		include('../elements/suc.php');
	}
}

if(isset($_POST['id_post'])){
	$bd->update('support_que',array('action'=>'on'),array('id'=>$_POST['id_post']));
	$rez=add_post($_POST['id_post'],$_POST['text']);
	if($rez){
		$suc=content('request_created');
		include('../elements/suc.php');
	}
}

include('../elements/sup_form_que.php');
include('../scripts/faq_style.php');
$supData=$bd->select('*','support_que',array('id_user'=>$_SESSION['id']),'ORDER BY `time` DESC',5);
if($supData){
	for($i=0;$i<sizeof($supData);$i++){
		echo'<div class="sup_que">';
		echo'<div><b>',content('created'),':</b> ',date('d.m.y',$supData[$i]['time']),' ';
		echo'<b>',content('type_que'),':</b> ',content($supData[$i]['type']),' ';
		echo'<b>',content('user'),':</b> ',$_SESSION['name'],'</div>';
			$supText=$bd->select('*','support_ans',array('id_que'=>$supData[$i]['id']),'ORDER BY `time`');
			if($supText){
				for($m=0;$m<sizeof($supText);$m++){
					echo'<div class="sup_ans">';
					echo'<table border="0"><tr><td rowspan="2"><b>';
					echo content('time'),':</b> ',date('d.m.y',$supText[$m]['time']),'<br>';
					$user=$bd->select('*','users',array('id'=>$supText[$m]['id_user']));
					echo'<b>',content('user'),':</b> ',$user[0]['name'];
					echo'</td></tr><tr><td>';
					echo'<div style="margin:0 0 0 15px;"><p>',$supText[$m]['text'],'</p></div></td></tr>';
					echo'</table></div>';
					
				}
			}
		echo'<div class="spoiler_head"><a class="spoiler_links">',content('reply');
		echo'</a><div class="spoiler_body">';
		$id_post=$supData[$i]['id'];
		include('../elements/answer.php');
		echo'</div></div>';
		echo'<div style="clear:both;"></div></div>';
	}
}
?>
<div style="clear:both;"></div>