    <form action="<?php echo $host_lang;?>/registration.php" method="post"><h3><? echo content('registration');?></h3>
    	<table cellpadding="10" cellspacing="0"  class="table_form" align="center">
            <tr>
          	
            <td><input class="text_pole" name="name" type="text" size="20" value="<? if(isset($_POST['name']))echo $_POST['name'];?>" /></td><td><? echo content('name');?></td>
          </tr>
          <tr >
          	
            <td><input class="text_pole" name="email" type="text" size="20" value="<? if(isset($_POST['email']))echo $_POST['email'];?>"/></td><td>email </td>
          </tr>
          <tr >
          	
            <td width="250">
            <label><input name="type" type="radio" value="advertiser" /> <? echo content('advertiser');?></label><br>
            <label><input name="type" type="radio" value="server_admin" /> <? echo content('server_admin');?></label></td><td><? echo content('choose_profile');?></td>
          </tr>
          	
            <td><input class="text_pole" name="pass" type="password" size="20"/></td><td ><? echo content('password');?></td>
          </tr>
          <tr >
          	
            <td><input class="text_pole" name="pass2" type="password" size="20" /></td><td><? echo content('pass_again');?></td>
          </tr>
         <tr >
           <td ><input style="float:right;" name="" class="button" type="submit" value="<? echo content('next');?>"></td>
		   <td></td>
          </tr>

        </table>
    </form>
