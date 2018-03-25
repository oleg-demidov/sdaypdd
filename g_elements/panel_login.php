<?php
$du = $_SESSION['user'];
$url_st = 'http://'.$_SERVER['HTTP_HOST'];
$default_logo = $url_st.'/images/def_logo.jpg';
$uri_buy = '<a class="button" href="'.$url_st.'/index.php?a=buy">Продлить обучение</a>';
$info_donate = '<div id="donateinfo">Включено до %s</div>';
$data_panel = array(
	'avatar' => (($du['avatar100'] != '')?$du['avatar100']:$default_logo),
	'name' => (($du['first_name'] != '')?$du['first_name'].' '.$du['last_name']:$du['name']),
	'email' => $du['email'],
	'donate' => (($du['donate'] < time())?$uri_buy:sprintf($info_donate,date("j.m.y g:i",$du['donate'])))
);

$results = get_user_results($_SESSION['user']['id'], $_SESSION['user']['type']);
$collb=get_count_que($_SESSION['user']['type']);
$pr = round(($results['bils_true']/($collb/100))/2 + ($results['tems_true']/($collb/100))/2);

$bd->get_all("SET @rownum = 0;");
$rate = $bd->get_all("SELECT  `r` FROM (SELECT `id_user`, (@rownum := @rownum + 1) AS `r` FROM `race_stat`  ORDER BY `p1` DESC, `p2` DESC, `p3` DESC, `p4` DESC, `last_race` DESC) AS `rate` WHERE `id_user`=?", array($_SESSION['user']['id']));
?>
<div class="panel_class">
  <img class="prava" src="http://<? echo $_SERVER['HTTP_HOST']?>/images/prava.png" />
  <div class="pavatar"><img src="<? echo $data_panel['avatar']?>" /></div>
  <div class="pname"><? echo $data_panel['name']?></div>
  <div class="pemail"><? echo $data_panel['email']?></div>
  <div class="pdonate">Готовность: <? echo $pr;?>%</div>
  <div class="prace"><?php if($rate) echo"Рейтинг в гонках: ", $rate[0]['r']; else echo"В гонках не участвовал(а)";?></div>
  <div class="plogout">
      
   
	<a href="http://<? echo $_SERVER['HTTP_HOST']?>/index.php?a=login&logout=1" class="button">Выход</a>
	 
  </div>

</div>
<!--<a class="button" style="margin:10px auto; width: 300px;" href="<? echo $url_st;?>/index.php?a=obuchenie">Продолжить обучение</a>-->