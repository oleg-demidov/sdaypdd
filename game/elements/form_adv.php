    <form action="<?php echo $host.$_SERVER['REQUEST_URI'];?>" method="post" enctype="multipart/form-data">
    	
          </tr><table cellpadding="10" cellspacing="0" border="0" class="table_form">
         
          <tr>
          	<td colspan="2"><input type="checkbox" name="adv_used" id="adv_used" value="on"<? if($data_server[0]['adv_used']=='on')echo'checked';?>/>
       	    <label for="adv_used"><? echo content('on_adv_server',$content_sa)?></label>
            <input type="text" name="s" id="1" value="on" hidden="hidden"/></td>
          </tr><tr><td><? echo content('plug',$content_sa)?>
          
         
        <?
	$data=$bd->select('*','companies',array('id_user'=>$_SESSION['id']));
	if($data){
	$s=sizeof($data);$i=0;
	echo'<select id="cap" name="cap"><option value="none"> </option>';
		while($i<$s){
			echo'<option value="'.$data[$i]['id'].'" ';
			if($data_server[0]['cap']==$data[$i]['id'])echo'selected';
			echo'>'.$data[$i]['header'].'</option>';
			$i++;
		} 
	echo'</select>';
	if($data_server[0]['cap']!=0)echo '</td><td><img src="',$host,'/advertiser/banner_creator/img_low.php?s=100&f=',$host,'/sprites/gifs/'.$data_server[0]['cap'].'.gif"/>';
	echo'</td></tr>';
	}
?>

<tr>
           <td colspan="2"><center><input name="" class="button" type="submit" value="<? echo content('save')?>"></center></td>
          </tr>
</table>
    </form>