    <form action="<?php echo $host.$_SERVER['REQUEST_URI'];?>" method="post">
    	
          <h3><? echo content('adding_priv',$content_sa);?></h3>
		   
          <?php if($error)echo'<br>'.$error;?>
		  <table cellpadding="10" cellspacing="0" border="0" class="table_form">
		  <tr>
          	
            <td><input name="name" type="text" class="text_pole" value="<? if(isset($data))echo $data['name'];?>" /></td><td><? echo content('name');?></td>
          </tr>
		   <tr>
            <td ><input name="price" type="text" class="text_pole" value="<? if(isset($data))echo $data['price'];?>" /></td><td ><? echo content('price_1day',$content_sa);?></td>
          </tr>
          </table>
          <table cellpadding="10" cellspacing="0" border="0" class="serv_tab">
         <th><? echo content('flag',$content_sa);?></th>
            <th><? echo content('on_off');?></th>
            <th><? echo content('description');?></th>
<?
$defDesk=$bd->select('*','priv_default_desk',array('type'=>$data_server[0]['type']));	$defDesk=$defDesk[0];
$need=array('ad','bd','cd','dd','ed','fd','gd','hd','id','jd','kd','ld','md','nd','od','pd','qd','rd','sd','td','ud','zd');
$desk=$bd->select($need,'privileges',array('id_server'=>$data_server[0]['id']));
$desk=$desk[0];
$sa=sizeof($an);
for($i=0;$i<$sa;$i++){
	echo'<tr';
	if($i%2)echo' class="nochet" ';
	echo'><td>',$an[$i],'</td>';
	echo'<td><input name="',$an[$i],'" type="checkbox" id="id',$an[$i],'" /></td>';
	echo'<td><label for="id',$an[$i],'">',content((($desk[$an[$i].'d']=='')?$defDesk[$an[$i]]:$desk[$an[$i].'d']),$content_sa),'</td></tr>';
	
}
?>
          
        </table> 
          
               
         <input name="" type="submit" class="button" value="<? echo content('save');?>">
		<div style="clear:both;"></div>

        
    </form>