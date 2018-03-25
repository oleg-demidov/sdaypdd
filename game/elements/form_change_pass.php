    <form action="<?php echo $host_lang;?>/remember_pass.php?kod=<? echo $_GET['kod'];?>" method="post">
	<h3><? echo content('change_pass');?></h3>
    	<table cellpadding="15" cellspacing="0" border="0" class="table_form">
                  	<tr>
          	
            <td><input name="pass1" class="text_pole" type="password" size="20"/></td>
			<td><? echo content('new_pass');?> </td>
          </tr>
          <tr>
		  
          	<td><input name="pass2" class="text_pole"  type="password" size="20" /></td>
			<td><? echo content('new_pass');?> </td>
          </tr>
         <tr>
		 
           <td colspan="3"><input name="" class="button" type="submit" value="Сохранить"></td>
          </tr>

        </table>
    </form>
