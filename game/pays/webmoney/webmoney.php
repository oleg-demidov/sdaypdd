<form action="https://merchant.webmoney.ru/lmi/payment.asp" method="post" accept-charset="windows-1251">
<?
$PURSE=$bd->select(array('value'),'variables',array('name'=>'PURSE'));
?>
<input type="hidden" type="text" name="LMI_PAYEE_PURSE" value="<? echo $PURSE[0]['value']?>"/>
<input type="hidden" type="text" name="LMI_PAYMENT_AMOUNT" value="<? echo $data['summ']?>"/>
<input type="hidden" type="text" name="LMI_PAYMENT_NO" value="<? echo $data['id']?>"/>
<input type="hidden" type="text" name="LMI_PAYMENT_DESC" value="<? echo $data['desc']?>"/>
<input type="hidden" type="text" name="LMI_PAYMENT_DESC_BASE64" value="<? echo base64_encode($data['desc'])?>"/>
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
<td><input type="text" class="text_pole" name="LMI_PAYMER_EMAIL" value="<? echo $user_email[0]['email']?>"></td>
</tr>
<tr><td colspan="2"><b><? echo content('wm_notif');?></b></td></tr>
<tr><td colspan="2">
<input type="submit" class="button" value="<? echo content('payment',$content_tn);?>" /></td></tr></table>
</form>