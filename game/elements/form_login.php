	<form action="<? echo $host,$_SERVER['REQUEST_URI'];?>" method="post" class="form_enter">
	<h3><? echo content('enter');?></h3>
    	<table cellpadding="10" cellspacing="0" class="table_form"  align="center">
        	<tr>
          		
            	<td><input name="email" class="text_pole" type="text" size="20" value="<? if(isset($_POST['email']))echo $_POST['email'];?>"/></td><td>email</td>
			</tr>
          		
            	<td><input name="pass" class="text_pole" type="password" size="20"/></td><td><? echo content('password');?></td>
			</tr>
            <tr >
           <td width="250" ><a href="<? echo $host_lang;?>/remember_pass.php"><? echo content('forgot_pass');?></a>
		   <input name="" class="button" type="submit" value="<? echo content('enter');?>"></td><td ></td>
          </tr>
        </table>
    </form>