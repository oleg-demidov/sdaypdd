<div class="header_panel">Информация</div>
<div class="panel_block">
	
	<div>

	<table   align="center" cellpadding="3" >
	 <tr>
	  <td align="center">
	  	<img alt="<? echo $_SESSION['user']['name'];?>" width="100" src="<? echo $_SESSION['user']['avatar100'];?>"/>
	  </td>
	 </tr>
	 <tr>
	  <td align="center">
	  	<?
		 if($_SESSION['user']['first_name'] != '')
		 	echo $_SESSION['user']['first_name'],' ',$_SESSION['user']['last_name'];
		 else echo $_SESSION['user']['name'];
		?>
	  </td>
	 </tr>
	 <tr>
	  <td>
	  	<?
		 if($_SESSION['user']['email'] != '')echo $_SESSION['user']['email'];
		?>
	  </td>
	 </tr>
	</table>
        <?php   if(false){ ?>
            <table align="center" style="border: 1px solid red;">
	<tr>
        <tr><td colspan="4" align="center">
	Внимание! В режиме "Гость" результаты не сохранятся. Авторизируйтесь.
              </td></tr>
          <tr>
	 <td>
        <script type="text/javascript" src="http://<?php echo $_SERVER['HTTP_HOST']?>/scr/soc_login.js"></script>
        <a id="vk_btn" class="social_btn2" title="Вконтакте" href="http://<? echo $hh?>/oauth/vk/auth.php"></a>
	 </td><td>
	<a id="ok_btn" class="social_btn2" title="Одниклассники" href="http://<? echo $hh?>/oauth/ok/auth.php"></a>
	 </td><td>
        <a id="mm_btn"  class="social_btn2" title="Мой мир" href="http://<? echo $hh?>/oauth/mm/auth.php"></a>
	 </td><td>
	<a id="ya_btn"  class="social_btn2" title="Яндекс" href="http://<? echo $hh?>/oauth/ya/auth.php"></a>
	 </td>
	</tr>
        </table>
        <?php }  ?>
        
	
	<table align="center" cellpadding="2" border="0">
	
	 <tr>
	  <td colspan="3" align="center" class="btns_abcd">
	  <?
	if(isset($_GET['type']) && ($_GET['type'] == 'ab' || $_GET['type'] == 'cd')){
		$_SESSION['user']['type'] = $_GET['type'];
		$bd->sql("UPDATE `users` SET `type`=? WHERE `id`=?", array($_GET['type'],$_SESSION['user']['id']));
	}
	if(isset($_GET['comm'])){
		$_SESSION['user']['comm'] = $_GET['comm'];
		$bd->sql("UPDATE `users` SET `comm`=? WHERE `id`=?", array($_GET['comm'],$_SESSION['user']['id']));
	}
	  ?>
	   <a   href="http://<? echo $_SERVER['HTTP_HOST'];?>/index.php?a=obuchenie&type=ab" title="Категория AB"  class="button<? if($_SESSION['user']['type'] == 'ab')echo' btnchk';?>" >AB</a>
	   <a   href="http://<? echo $_SERVER['HTTP_HOST'];?>/index.php?a=obuchenie&type=cd" title="Категория CD"  class="button<? if($_SESSION['user']['type'] == 'cd')echo' btnchk';?>" >CD</a>
	   
	  </td>
	 </tr>
	 <tr>
	 	<td colspan="3" align="center">
		<a   href="http://<? echo $_SERVER['HTTP_HOST'];?>/index.php?a=obuchenie&comm=<? if($_SESSION['user']['comm'])echo'0';else echo'1';?>" title="Вкл/Выкл подсказки к вопросам"  class="button<? if($_SESSION['user']['comm'])echo' btnchk';?>" >Подсказки</a>
		</td>
	 </tr>
	 <tr>
	  <td colspan="2">
	   Количество вопросов
	   <? 
	$results = get_user_results($_SESSION['user']['id'], $_SESSION['user']['type']);
	?>
	  </td>
	  <td>
	   <b><? echo $collb=get_count_que($_SESSION['user']['type']);?></b>
	  </td>
	 </tr>
	 <tr>
	  <td></td>
	  <td>Билеты</td>
	  <td>Темы</td>
	 </tr>
	 <tr class="right_ans">
	  <td>Пройдено</td>
	  <td><? echo $results['bils_true'];?></td>
	  <td><? echo $results['tems_true'];?></td>
	 </tr>
	 <tr class="false_ans">
	  <td>Ошибок</td>
	  <td><? echo $results['bils_false'];?></td>
	  <td><? echo $results['tems_false'];?></td>
	 </tr>
	 <tr class="false_ans">
	  <td colspan="3" align="center">
	   <? $pr = round(($results['bils_true']/($collb/100))/2 + ($results['tems_true']/($collb/100))/2); ?>
	   <img  src="http://<? echo $_SERVER['HTTP_HOST']?>/scr/speedometer/speedometr.php?v=<? echo $pr;?>" />
	  </td>
	 </tr>
	</table>
	</div>
</div>