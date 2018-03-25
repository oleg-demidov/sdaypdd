<?if(isset($_SESSION['user']['id'])){?>
<table cellpadding="3" style="float: left; display: block; margin:0 10px 0 0;" align="left">
		<tr>
		 <td>
		  <img alt="<? echo $_SESSION['user']['name'];?>" src="<? echo $_SESSION['user']['avatar100']; ?>"/>
		 </td>
                
                
                </tr><tr>
                    <td style="font-size: 0.7em; text-align: center;">
		<? if($_SESSION['user']['first_name'] == NULL)echo $_SESSION['user']['name'];
                    else echo $_SESSION['user']['first_name'],'<br>',$_SESSION['user']['last_name']; ?>
		</td>
                <tr>		
</table><p><?  echo stripslashes($CONT->data['text'])?></p><div></div>
<form action="<? echo "http://",$_SERVER['HTTP_HOST'],"/index.php?a=add_que";?>" method="post" class="form_que">
    Заголовок (кратко, суть вопроса)<br><input type="text" value="<? if(isset($_POST['stext']))echo $_POST['stext']?>" name="header" id="header_que">
    Текст (развернуто, вопрос)<br><textarea id="text_que" name="text"></textarea>
    <input type="submit" class="button" value="Задать вопрос">
</form>
<?}
