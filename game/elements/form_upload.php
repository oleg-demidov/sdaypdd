    <form action="<?php echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];?>" method="post" enctype="multipart/form-data">
    	<table cellpadding="10" cellspacing="0" border="1" class="table_adv">
          <tr class="tr_top">
           <td colspan="2"><center>Добавление модели</center></td>
          </tr>
          <tr>
          	<td>Название </td>
            <td><input name="name" type="text" size="20" value="<? if(isset($data['name']))echo $data['name'];?>" /></td>
          </tr>
          <tr>
          	<td>Файл модели</td>
            <td><label for="models"></label>
            <input type="file" name="models" id="models" /></td>
          </tr>
         
          <tr>
            <td>Файл картинки</td>
            <td><label for="icons"></label>
            <input type="file" name="icons" id="icons" /></td>
          </tr>
          <tr>
            <td colspan="2"><input type="checkbox" name="uni" id="uni" />
            <label for="uni">уникальная модель</label>              <label for="uni"></label></td>
          </tr>
          
               
         <tr class="tr_grey">
           <td colspan="2"><center><input name="" type="submit" value="Сохранить"></center></td>
          </tr>

        </table>
    </form>