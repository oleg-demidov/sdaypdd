<form action="<? echo "http://",$_SERVER['HTTP_HOST'],"/index.php?a=reg";?>" method="post" class="form_enter">
<table align="center"><tr><td>
<table cellpadding="7">
 <tr>
  <td colspan="2">
   <h3>Регистрация</h3>
  </td>
 </tr>
 <tr>
  <td colspan="2"><div id="error_msg"><? if(isset($error))echo $error;?></div></td>
 </tr>
 <tr>
  <td><input name="name" class="text_pole" type="text"  value="<? if(isset($_POST['name']))echo $_POST['name'];?>"/>
  <div>Имя</div>
  </td>
  
 </tr>
 <tr>
  <td><input name="email" class="text_pole" type="text"  value="<? if(isset($_POST['email']))echo $_POST['email'];?>"/><div>Email</div>
  </td>
  
 </tr>
 <tr>
  <td><input name="pass" class="text_pole" type="password" /><div>Пароль</div></td>
  
 </tr>
 <tr>
  <td><input name="pass2" class="text_pole" type="password" /><div>Повторить пароль</div></td>
  
 </tr>
 <tr>
  <td style="background:#FFFFFF;"><div><input name="captcha" class="text_pole" type="text" />
  <img style="margin:1px;" src="login/capcha/captcha.php" id="captcha" /></div></td>
  
 </tr>
 <tr>
  <td width="250" colspan="2">
   <input name="" class="button" type="submit" value="Регистрация">
  </td>
 </tr>
</table>
</td></tr></table>
</form>