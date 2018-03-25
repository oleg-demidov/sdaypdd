<table style="margin-top:5px; width:825px; float:left;" cellpadding="0"  cellspacing="0" border="0" class="serv_tab">
<?

$fm=strtotime('first day of this month');
$fw=strtotime('first day of this week');
$ft=strtotime('today 00:00:00');
$q1=" `time`>".$fm;
$q2=" `time`>".$fw;
$q3=" `time`>".$ft;
$tosel="from `adv_stat` where `id_user`='".$_SESSION['id']."' AND ";
$sel1="select COALESCE(sum(`show`),0) ".$tosel;
$sel2="select COALESCE(sum(`unishow`),0) ".$tosel;
$sel3="select COALESCE(sum(`click`),0)".$tosel;
$sel4="select COALESCE(sum(`uniclick`),0) ".$tosel;
$quer="select (".$sel1.$q1."),(".$sel2.$q1."),(".$sel3.$q1."),(".$sel4.$q1."),";
$quer.="(".$sel1.$q2."),(".$sel2.$q2."),(".$sel3.$q2."),(".$sel4.$q2."), ";
$quer.="(".$sel1.$q3."),(".$sel2.$q3."),(".$sel3.$q3."),(".$sel4.$q3.")";
$quer.=" from `adv_stat`";
$tdata=$bd->sql_query($quer);
$tdata=$bd->get_result($tdata,MYSQL_NUM);
$quer="select (select `value` from `variables` where `name`='price1000'),";
$quer.="(select `value` from `variables` where `name`='price_click') from `variables`";
$quer.=" limit 1";
$vari=$bd->sql_query($quer);
$vari=$bd->get_result($vari,MYSQL_NUM);
?>
<tr>
 <th></th>
 <th><? echo content('Views',$content_adv);?></th>
 <th><? echo content('unique_views',$content_adv);?></th>
 <th><? echo content('transitions',$content_adv);?></th>
 <th><? echo content('unique_transitions',$content_adv);?></th>
 <th><? echo content('deducted',$content_adv);?></th>
</tr>
<tr>
 <td><? echo content('month',$content_adv);?></td>
 <td class="td11"><? echo $tdata[0][0];?></td>
 <td class="td11"><? echo $tdata[0][1];?></td>
 <td class="td11"><? echo $tdata[0][2];?></td>
 <td class="td11"><? echo $tdata[0][3];?></td>
 <td class="tds"><? echo round((($vari[0][0]/1000*$tdata[0][1])+($vari[0][1]*$tdata[0][3])),2);?> $</td>
</tr>
<tr class="nochet">
 <td><? echo content('week',$content_adv);?></td>
 <td class="td11"><? echo $tdata[0][4];?></td>
 <td class="td11"><? echo $tdata[0][5];?></td>
 <td class="td11"><? echo $tdata[0][6];?></td>
 <td class="td11"><? echo $tdata[0][7];?></td>
 <td class="tds"><? echo round((($vari[0][0]/1000*$tdata[0][5])+($vari[0][1]*$tdata[0][7])),2);?> $</td>
</tr>
<tr>
 <td><? echo content('today',$content_adv);?></td>
 <td class="td11"><? echo $tdata[0][8];?></td>
 <td class="td11"><? echo $tdata[0][9];?></td>
 <td class="td11"><? echo $tdata[0][10];?></td>
 <td class="td11"><? echo $tdata[0][11];?></td>
 <td class="tds"><? echo round((($vari[0][0]/1000*$tdata[0][9])+($vari[0][1]*$tdata[0][11])),2);?> $</td>
</tr>
</table>