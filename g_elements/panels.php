<?php
	//include ('soc_buttons.php');
        if(!isset($_SESSION['user']['id'])||isset($_SESSION['user']['gost']))include ('soc_login.php');
	if((isset($_GET['cat']) && $CONT->razdel == 'pdd') || $CONT->razdel == 'razm' || $CONT->razdel == 'signs'  )include('pdd_panel.php');
	if((!isset($_SESSION['user']['id'])|| isset($_SESSION['user']['gost'])) && $CONT->razdel != 'obuchenie')include ('banner_obuch.php');
	if(isset($_SESSION['user']['id'])&&($CONT->razdel == 'obuchenie' || $CONT->razdel == 'test'))include ('user_panel.php');
	if($CONT->razdel == 'race')include ('panel_race.php');
	//include('adsblock_adaptive.php');
        include('visitors.php');
	//include('wmbtns.php');
	//include('chat.php');
	//include('links.php');
?>
