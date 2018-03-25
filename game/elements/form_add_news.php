    <form action="<?php echo $host.$_SERVER['REQUEST_URI'];?>" method="post">
	 <h3>Новость</h3>
    	<table cellpadding="10" cellspacing="0" class="table_form">
         
           <tr>
          	<td colspan="2">Заголовок<br>
       	     <input name="header" type="text" class="text_pole" size="20" value="<? if(isset($_POST['header']))echo $_POST['header'];?>" /></td>
          </tr>
          <tr class="tr_grey">
          	<td colspan="2">Сообщение<br>
            <textarea name="mess" class="text_pole" rows="10">
            <? if(isset($_POST['mess']))echo $_POST['mess'];?>
            </textarea></td>
          </tr>
         
         <tr class="tr_grey">
           <td><input name="" class="button" type="submit" value="Отправить"></td>
		   <td></td>
          </tr>

        </table>
    </form>