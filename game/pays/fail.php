<?
$trans=$bd->select('*','transactions',array('id'=>$_GET['id']));
?>
<table border="0" align="center" cellpadding="10">
<tr><td><? echo content('no_schet')?></td><td><b><? echo $trans[0]['id']?></b></td></tr>
<tr><td><? echo content('summ',$content_tn)?></td><td><b><? echo $trans[0]['summ']?></b></td></tr>
<tr><td><? echo content('time')?></td><td><b><? echo date('d.m.y H:i',$trans[0]['time'])?></b></td></tr>
<tr><td><? echo content('sposob',$content_tn)?></td><td><b><? echo $trans[0]['sposob']?></b></td></tr>
<tr><td><? echo content('description')?></td><td><b><? echo $trans[0]['desc']?></b></td></tr>
<tr><td colspan="2"><b><span style="color:#FF6C6C;font-size:18px;"><? echo content('no_paid',$content_tn)?></span></b><br><a class="button" href="<? echo $host_lang?>/pays/index.php?a=pay&id=<? echo $trans[0]['id']?>"><? echo content('payment',$content_tn)?></a></td></tr>
</table>
