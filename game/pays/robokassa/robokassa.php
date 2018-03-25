<form action="https://merchant.roboxchange.com/Index.aspx" method="post"/>
<?php
$mrh_login = "cs-money.net";
$mrh_pass1 = "csmoney2015";
$shp_item = "1";
$crc  = md5($mrh_login.":".$data['summ'].":".$data['id'].":".$mrh_pass1.":Shp_item=".$shp_item);
?>
<input type="hidden" name="IncCurrLabel" value=""/>
<input type="hidden" name="MrchLogin" value="<? echo $mrh_login?>"/>
<input type="hidden" name="OutSum" value="<? echo $data['summ']?>"/>
<input type="hidden" name="InvId" value="<? echo $data['id']?>"/>
<input type="hidden" name="Desc value" value="<? echo $data['desc']?>"/>
<input type="hidden" name="Culture" value="<? echo $lang;?>"/>
<input type="hidden" name="Shp_item" value="<? echo $shp_item?>"/>
<input type="hidden" name="SignatureValue" value="<? echo $crc?>"/>
<table align="center" border="0" cellpadding="10">
<tr><td><? echo content('summ',$content_tn)?></td>
<td><b><? echo $data['summ'];?></b></td>
</tr>
<tr><td><? echo content('name_pay',$content_tn)?></td>
<td><b><? echo $data['desc']?></b></td>
</tr>
<tr><td>Email</td>
<? 
	if($data['direction']=='in')
		$user_email=$bd->select(array('email'),'users',array('id'=>$data['id_user']));
	elseif($data['direction']=='adminka')
		$user_email=$bd->select(array('email'),'admins',array('id'=>$data['id_adminka']));
?>
<td><input type="text" class="text_pole" name="sEmail" value="<? echo $user_email[0]['email']?>"></td>
</tr>
<tr><td colspan="2"><b><? echo content('rb_notif',$content_tn);?></b></td></tr>
<tr><td colspan="2">
<input type="submit" class="button" value="<? echo content('payment',$content_tn);?>" /></td></tr></table>
</form>