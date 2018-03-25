<h3><? echo content('support')?></h3>
<?

if(isset($_POST['id_post'])){
	$bd->update('support_que',array('action'=>'off'),array('id'=>$_POST['id_post']));
	$Adata=array(
		'text'=>$_POST['text'],
		'id'=>rand(0,9999999),
		'time'=>time(),
		'id_user'=>$_SESSION['id'],
		'id_que'=>$_POST['id_post']
	);
	include('../scripts/email_func.php');
	$content_sup=get_cont($lang,'support');
	$text=content('ans_send_text',$content_sup).'<br>'.$_POST['text'];
	$subj='Cs-money.net '.content('support');;
	$sup_que=$bd->select('*','support_que',array('id'=>$_POST['id_post']));
	$user_que=$bd->select('*','users',array('id'=>$sup_que[0]['id_user']));
	send_mail($user_que[0]['email'], $subj, $text);
	$rez=$bd->insert('support_ans',$Adata);
	if($rez){
		$suc='Ответ отправлен ('.$user_que[0]['email'].')';
		include('../elements/suc.php');
	}
}
include('../scripts/faq_style.php');
$supData=$bd->select('*','support_que',array('action'=>'on'),'ORDER BY `time` DESC',5);
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