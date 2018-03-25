    <form action="<?php echo $host_lang;?>/profile.php" method="post">
	<h3><? echo content('profile');?></h3>
    	<table cellpadding="15" cellspacing="0" border="0" class="table_form">
          
           <tr>
          	
            <td colspan="2"><input name="name" class="text_pole" type="text" size="50" value="<? echo $data_p[0]['name'];?>" /></td>
			<td><? echo content('name');?></td>
          </tr>
          <tr>
          	<td colspan="2"><input name="email" class="text_pole" type="text" size="50" value="<? echo $data_p[0]['email'];?>"/></td>
			<td>email</td>
          </tr>
          <tr>
            <td colspan="2"><input name="purse" class="text_pole" type="text" size="50" value="<? echo $data_p[0]['purse'];?>"/></td>
			<td><? echo content('purse');?></td>
          </tr>
          <tr>
          	<td colspan="2">
            <label><input name="type" type="radio" value="advertiser" <? if($data_p[0]['type']=='advertiser')echo'checked';?>/> <? echo content('advertiser');?></label><br>
            <label><input name="type" type="radio" value="server_admin" <? if($data_p[0]['type']=='server_admin')echo'checked';?>/> <? echo content('server_admin');?></label></td>
			<td><? echo content('choose_profile');?></td>
          </tr>
          	<tr>
			<td rowspan="2" ><? echo content('change_pass');?></td>
          	
            <td><input name="pass_old" class="text_pole" type="password" size="20"/></td>
			<td><? echo content('old_pass');?> </td>
          </tr>
          <tr>
		  
          	<td><input name="pass" class="text_pole"  type="password" size="20" /></td>
			<td><? echo content('new_pass');?> </td>
          </tr>
         <tr>
		 
           <td colspan="3"><input name="" class="button" type="submit" value="<? echo content('save');?>"></td>
          </tr>

        </table>
    </form>
