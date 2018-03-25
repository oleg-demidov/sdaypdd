    <form action="<?php echo $host.$_SERVER['REQUEST_URI'];?>" method="post">
    	
          <h3><? echo content('generate_codes',$content_sa);?></h3>
		   
          <?php if($error)echo'<br>'.$error;?>
		  <table cellpadding="10" cellspacing="0" border="0" class="table_form">
		   
		  <tr>
            <td>
<select name="offer" id="offer">
<?
	$offarr=$bd->select(array('id','name'),'offers',array('id_server'=>$_GET['server']));
	$sg=sizeof($offarr);
	for($i=0;$i<$sg;$i++){
		echo'<option value="'.$offarr[$i]['id'].'">';
		echo $offarr[$i]['name'].'</option>';
	}
?></select></td><td><? echo content('select_priv',$content_sa);?></td>
          </tr> <tr>
            <td ><input name="days" type="text" class="text_pole" value="" /></td><td><? echo content('number_days',$content_sa);?></td>
          </tr>
          </table>
          
          
               
         <input name="" type="submit" class="button" value="<? echo content('generate',$content_sa);?>">
		<div style="clear:both;"></div>

        
    </form>