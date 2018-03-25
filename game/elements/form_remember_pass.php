 <form action="<?php echo $host_lang;?>/remember_pass.php" method="get">
 <h3><? echo content('save_pass',$content_sp);?></h3>
    	<table cellpadding="10" cellspacing="0" border="0" class="table_form" align="center" >
          <tr>
            <td><input name="email" class="text_pole" type="text" size="20" value="" /></td>
			<td><? echo content('enter_y_email',$content_sp);?></td>
          </tr>
          </table><input name="" type="submit" class="button" value="<? echo content('next');?>">
	 <div style="clear:both;"></div>
    </form>