<table style="float:left;width:520px; margin:5px 0 5px 5px;" cellpadding="0"  cellspacing="0" border="0" class="serv_tab">
<?
	$fp=strtotime('first day of previous month');
	$lp=strtotime('first day of this month');
	$ft=strtotime('first day of this month');
	$lt=strtotime('first day of next month');
	$tnow=" (`timeout`-60*60*24*(`days`-1)) ";
	$iu=" `id_user`=".$_SESSION['id'];
	$fpre="from `admins` where ".$iu ;
	$spre1="select count(*) ".$fpre." and ";
	$spre2="select sum(money) ".$fpre." and ";
	$qs1=$spre1.$tnow.">".$fp." and ".$tnow."<".$lp;
	$qs2=$spre2.$tnow.">".$fp." and ".$tnow."<".$lp;
	$qs3=$spre1.$tnow.">".$ft." and ".$tnow."<".$lt;
	$qs4=$spre2.$tnow.">".$ft." and ".$tnow."<".$lt;
	$quer="select count(*), sum(money),(".$qs1."),(".$qs2."),(".$qs3."),(".$qs4.")".$fpre;
	$adm_lm=$bd->sql_query($quer);
	$adm_lm=$bd->get_result($adm_lm,MYSQL_NUM);
	$spre1="select COALESCE(sum(`unishow`),0) from `adv_stat` where ";
	$spre2="select COALESCE(sum(`uniclick`),0) from `adv_stat` where ";
	$q1=$spre1." `time`>".$fp." and `time`<".$lp." and".$iu;
	$q2=$spre2." `time`>".$fp." and `time`<".$lp." and".$iu;
	$q3=$spre1." `time`>".$ft." and `time`<".$lt." and".$iu;
	$q4=$spre2." `time`>".$ft." and `time`<".$lt." and".$iu;
	$quer="select COALESCE(sum(`unishow`),0),COALESCE(sum(`uniclick`),0),(".$q1."),(".$q2."),(".$q3."),(".$q4.") from `adv_stat` where ".$iu;
	$adv_lm=$bd->sql_query($quer);
	$adv_lm=$bd->get_result($adv_lm,MYSQL_NUM);
	$quer="select (select `value` from `variables` where `name`='precent_adm'),";
	$quer.="(select `value` from `variables` where `name`='price1000'),";
	$quer.="(select `value` from `variables` where `name`='price_click') from `variables`";
	$quer.=" limit 1";
	$vari=$bd->sql_query($quer);
	$vari=$bd->get_result($vari,MYSQL_NUM);
?>
		<tr><th></th><th colspan="2"><? echo content('activ_adminok',$content_sa);?></th><th colspan="2"><? echo content('views_transitions',$content_sa);?></th></tr>
		<tr><td width="133"><? echo content('in_the_last_month',$content_sa);?></td>
		<td class="td11"><? echo $adm_lm[0][2]?></td>
		<td class="tds"><? echo $adm_lm[0][3]?> $</td>
		<td class="td11"><? echo $adv_lm[0][2]?>/<? echo $adv_lm[0][3]?></td>
		<td class="tds">
		<? 
		echo round(($vari[0][0]/100)*($vari[0][1]/1000*$adv_lm[0][2]+$adv_lm[0][3]*$vari[0][2]),2);
		?> $</td></tr>
		<tr class="nochet"><td><? echo content('this_month',$content_sa);?></td>
		<td class="td11"><? echo $adm_lm[0][4]?></td>
		<td class="tds"><? echo $adm_lm[0][5]?> $</td>
		<td class="td11"><? echo $adv_lm[0][4]?>/<? echo $adv_lm[0][5]?></td>
		<td class="tds">
		<? 
		echo round(($vari[0][0]/100)*($vari[0][1]/1000*$adv_lm[0][4]+$adv_lm[0][5]*$vari[0][2]),2);
		?>
		 $</td></tr>
		<tr><td><? echo content('all_Time',$content_sa);?></td>
		<td class="td11"><? echo $adm_lm[0][0]?></td>
		<td class="tds"><? echo $adm_lm[0][1]?> $</td>
		<td class="td11"><? echo $adv_lm[0][0]?>/<? echo $adv_lm[0][1]?></td>
		<td class="tds">
		<? 
		echo round(($vari[0][0]/100)*($vari[0][1]/1000*$adv_lm[0][0]+$adv_lm[0][1]*$vari[0][2]),2);
		?>
		 $</td></tr>
</table>