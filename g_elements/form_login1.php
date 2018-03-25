	<form action="<? echo "http://",$_SERVER['HTTP_HOST'],"/index.php?a=login";?>" method="post" class="form_enter">
	<table align="center"><tr><td>
	<h3>Вход</h3>

   <div id="error_msg">
   <? if($error != '')echo $error; 
   if(isset($_GET['error']))echo $_GET['error'];
   if(isset($_GET['err_soc']))echo 'Попробуйте другую сеть';?></div>
 <table class="tbl_social" cellpadding="7">
    <tr>
	 <td>
	<? $host ='http://'. $_SERVER['HTTP_HOST']?>
	<a id="vk_btn" class="social_btn" title="Вконтакте" href="<? echo $host?>/oauth/vk/auth.php"></a>
	 </td><td>
	<a id="mm_btn" class="social_btn" title="Мой мир" href="<? echo $host?>/oauth/mm/auth.php"></a>
	 </td></tr><tr><td>
	<a id="ok_btn" class="social_btn" title="Одниклассники" href="<? echo $host?>/oauth/ok/auth.php"></a>
	 </td><td>
	<a id="ya_btn" class="social_btn" title="Яндекс" href="<? echo $host?>/oauth/ya/auth.php"></a>
	 </td>
	</tr>
   </table>
	
<table cellpadding="7" cellspacing="0"  >
 <tr>

  <td colspan="2"><input name="email" class="text_pole" type="text"  value="<? if(isset($_POST['email']))echo $_POST['email'];?>"/><div>Email</div></td> 
  
 </tr>
 <tr>
 
  <td colspan="2"><input name="pass" class="text_pole" type="password" /><div>Пароль</div></td>
 </tr>
 <tr>
  <td>
   <a href="<? echo "http://",$_SERVER['HTTP_HOST'];?>/index.php?a=remem_pass">Забыли пароль?</a>
  </td>
  <td>
     <input name="" class="button" type="submit" value="Вход">
  </td>
 </tr>
  <tr>
  <td colspan="2">
   <a href="<? echo "http://",$_SERVER['HTTP_HOST'];?>/index.php?a=reg">Зарегистрироваться</a>
  </td>
 </tr>
</table>

</td></tr></table>

    </form>