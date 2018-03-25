 <form action="<?php echo $host.$_SERVER['REQUEST_URI'];?>" method="post">
 <h3><? echo content('adding_admin',$content_sa);?></h3>
    	<table cellpadding="10" cellspacing="0" border="0" class="table_form" >
          
          <tr>
            <td colspan="3"><input name="name" class="text_pole" type="text" size="20" value="" /></td>
			<td><? echo content('nick');?> *</td>
          </tr>
          <tr>
            <td colspan="3"><input name="age" class="text_pole" type="text" size="20" value="" /></td>
			<td><? echo content('age');?> *</td>
          </tr>
          <tr>
		  <td colspan="3"><input name="ip" class="text_pole" type="text" size="20" value="" /></td>
          	<td>IP</td>
            
          </tr>
          <tr><td colspan="3"><input name="steam" class="text_pole" type="text" size="20" value="" /></td>
          	<td>Steam_id</td>
            
          </tr>
          <tr><td colspan="3"><input name="email" class="text_pole" type="text" size="20" value="" /></td>
          	<td>Email *</td>
            
          </tr>
          <tr> <td colspan="3"><input name="pass" class="text_pole" type="text" size="20" value="" /></td>
          	<td><? echo content('password');?> *</td>
           
          </tr>
          <tr><td colspan="3"><input name="days" class="text_pole" type="text" size="20" value="" /></td>
          	<td><? echo content('time_in_days',$content_sa);?> *</td>
            
          </tr></table>
        <table cellpadding="0" cellspacing="0" border="0" class="serv_tab">
		<tr>
			<th><? echo content('flag',$content_sa);?></th>
			<th><? echo content('on_off');?></th>
			<th><? echo content('description');?></th>
		</tr>
<?
$sf=sizeof($need_flags);
for($i=0;$sf>$i;$i++){
	echo'<tr';
	if($i%2)echo' class="nochet" ';
	echo'><td>',$need_flags[$i],'</td>';
	echo'<td><input id="',$need_flags[$i],'" name="',$need_flags[$i],'" type="checkbox"';
	echo' /></td><td><label for="',$need_flags[$i],'">';
	if($data[$need_flags[$i].'d']=='')echo content($cust_desc[$need_flags[$i]],$content_sa);
	else echo $data[$need_flags[$i].'d'];
	echo'</label></td></tr>';
}
?>
	 </table><input name="" type="submit" class="button" value="<? echo content('save');?>">
	 <div style="clear:both;"></div>
    </form>