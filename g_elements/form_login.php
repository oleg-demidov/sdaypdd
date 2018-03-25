	<form action="<? echo "http://",$_SERVER['HTTP_HOST'],"/index.php?a=login";?>" method="post" class="form_enter">
              <table align="center" class="tbl_social" cellpadding="7"><tr><td colspan="4">
	<h3>Авторизация</h3>

   <div id="error_msg">
   <?php if($error != '')echo $error; 
   if(isset($_GET['error']))echo $_GET['error'];
   if(isset($_GET['err_soc']))echo 'Попробуйте другую сеть';?></div>
                </td></tr>
            <tr>
	 <td>
	<? $host ='http://'. $_SERVER['HTTP_HOST']?>
	<a id="vk_btnl" class="social_btn" title="Вконтакте" href="<? echo $host?>/oauth/vk/auth.php"></a>
	 </td><td>
	<a id="mm_btnl" class="social_btn" title="Мой мир" href="<? echo $host?>/oauth/mm/auth.php"></a>
	 </td><td>
	<a id="ok_btnl" class="social_btn" title="Одниклассники" href="<? echo $host?>/oauth/ok/auth.php"></a>
	 </td><td>
	<a id="ya_btnl" class="social_btn" title="Яндекс" href="<? echo $host?>/oauth/ya/auth.php"></a>
	 </td>
	</tr>
   </table>


    </form>