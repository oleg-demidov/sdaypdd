<?
$trans=$bd->select('*','transactions',array('id'=>$_GET['id']));
?>
<table border="0" align="center" cellpadding="10">
<tr><td><? echo content('no_schet')?></td><td><b><? echo $trans[0]['id']?></b></td></tr>
<tr><td><? echo content('summ',$content_tn)?></td><td><b><? echo $trans[0]['summ']?></b></td></tr>
<tr><td><? echo content('time')?></td><td><b><? echo date('d.m.y H:i',$trans[0]['time'])?></b></td></tr>
<tr><td><? echo content('sposob',$content_tn)?></td><td><b><? echo $trans[0]['sposob']?></b></td></tr>
<tr><td><? echo content('description')?></td><td><b><? echo $trans[0]['desc']?></b></td></tr>
<tr><td colspan="2"><b><span style="color:#00CC33;font-size:18px;"><? echo content('paid',$content_tn)?></span></b><br><? if($trans[0]['direction']=='in')
echo'<a class="button" href="'.$host_lang.'/balance/index.php">'.content("next").'</a>'; ?></td></tr>
</table>
