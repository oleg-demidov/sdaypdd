<div class="menu2">
<?
$counts=$bd->get_result($bd->sql_query("SELECT count(*) AS `summ` FROM `support_que` WHERE `action`='on'"));
$countm=$bd->get_result($bd->sql_query("SELECT count(*) AS `summ` FROM `maps_no`"));
$countu=$bd->get_result($bd->sql_query("SELECT count(*) AS `summ` FROM `users`"));
$countc=$bd->get_result($bd->sql_query("SELECT count(*) AS `summ` FROM `companies` WHERE `activ`='off'"));
$countv=$bd->get_result($bd->sql_query("SELECT count(*) AS `summ` FROM `transactions` WHERE `direction`='out' AND `status`='no_paid'"));
?>
<a href="<? echo $host_lang;?>/adminka/index.php?a=users&limit_u=0">Аккаунты (<? echo $countu[0]['summ']?>)</a>
<a href="<? echo $host_lang;?>/adminka/index.php?a=content_keys">Контент ключи</a>
<a href="<? echo $host_lang;?>/adminka/index.php?a=content">Контент</a>
<a href="<? echo $host_lang;?>/adminka/index.php?a=vars">Переменные</a>
<a href="<? echo $host_lang;?>/adminka/index.php?a=companies">Кампании <? echo $countc[0]['summ']?></a>
<a href="<? echo $host_lang;?>/adminka/index.php?a=maps_no">Карты <? echo $countm[0]['summ']?></a>
<a href="<? echo $host_lang;?>/adminka/index.php?a=support">Поддержка <? echo $counts[0]['summ']?></a>
<a href="<? echo $host_lang;?>/adminka/index.php?a=viplati">Выплаты <? echo $countv[0]['summ']?></a>
</div>