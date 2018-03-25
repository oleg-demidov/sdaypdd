<?
 $reqst=preg_split("(/ru|/en)", $_SERVER['REQUEST_URI'], NULL, PREG_SPLIT_NO_EMPTY);
	if(isset($reqst[0]))$reqst=$reqst[0];
?>
<a href="<? echo $host;?>/ru<? echo $reqst;?>"><img src="<? echo $host;?>/images/ru.gif"></a>
<a href="<? echo $host;?>/en<? echo $reqst;?>"><img src="<? echo $host;?>/images/en.gif"></a>
