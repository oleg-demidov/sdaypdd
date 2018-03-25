<?php 
	include('scr/classes.php');
	$CONT = new CONTENT($bd);
	$title = $CONT->title;
	include('g_elements/head.php');
	include('g_elements/header.php');
	include('g_elements/content.php');
	include('g_elements/scroolbtn.php');
	include('g_elements/footer.php');
?>