 <form action="<?php echo $host_lang;?>/email_check.php" method="get">
 <h3><? echo content('check_email');?></h3>
    	<table cellpadding="10" cellspacing="0" border="0" class="table_form" >
          
          <tr>
            <td><input name="kod" class="text_pole" type="text" size="20" value="" /></td>
			<td><? echo content('check_email_text');?>(<b><? echo $email; ?></b>)*</td>
          </tr>
		  <tr>
		  <td><a href="<?php echo $host_lang;?>/email_check.php?send_kod=1&email=<? echo $email;?>&id_user=<? echo $id_user;?>"><? echo content('no_letter');?></a></td>
		  <td></td>
		  </tr>
          </table><input name="" type="submit" class="button" value="<? echo content('next');?>">
	 <div style="clear:both;"></div>
    </form>