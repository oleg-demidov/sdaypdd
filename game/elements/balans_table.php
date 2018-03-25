<table style=" margin:5px 0 0 5px;float:left; width:150px;" cellpadding="0"  cellspacing="0" border="0" class="serv_tab">
    <tr><th><? echo content('balance');?></th></tr>
	<tr><td style="padding:5px 10px;;">
	<span style="font-size:20px; line-height:20px; color:#FF9933;">
	<? $balans=$bd->select('balans','users',array('id'=>$_SESSION['id'])); 
	echo  round($balans[0]['balans'], 2); ?> <span style="font-size:12px;">wmz</span></span>
</td>
<tr class="nochet"><td><? echo content('fill');?></td></tr>
<tr><td><? echo content('vivesti');?></td></tr>
</table>