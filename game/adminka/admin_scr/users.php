<form action="<?php echo $host_lang.$_SERVER['REQUEST_URI'];?>" method="post"><input name="" class="button" type="submit" value="Поиск"><input name="search" value="" type="text" style="float:right; margin:5px;" class="text_pole"/></form>
<?
	if(isset($_GET['id_user'])){
		if(isset($_POST['del'])){
			$bd->delete_str('users',array('id'=>$_GET['id_user']));
			$ser=$bd->select('id','servers',array('id_user'=>$_GET['id_user']));
			if($ser){
				$ss=sizeof($ser);
				for($i=0;$i<$ss;$i++){
					$bd->delete_str('servers',array("id"=>$ser[$i]['id']));
					$bd->delete_str('privileges',array("id_server"=>$ser[$i]['id']));
					$bd->delete_str('offers',array("id_server"=>$ser[$i]['id']));
					$bd->delete_str('admins',array("id_server"=>$ser[$i]['id']));
					$bd->delete_str('promo_keys',array("id_server"=>$ser[$i]['id']));
				}
			}
			$com=$bd->select('id','companies',array('id_user'=>$_GET['id_user']));
			if($com){
				$sc=sizeof($ser);
				for($i=0;$i<$sc;$i++){
					$bd->delete_str('companies',array("id"=>$com[$i]['id']));
					unlink('../sprites/'.$com[$i]['id'].'.spr');
					unlink('../sprites/'.$com[$i]['id'].'.vtf');
					unlink('../sprites/gifs/'.$com[$i]['id'].'.gif');
				}
			}
		}else{
			$dataq=$_POST;
			$bd->update('users',$dataq,array('id'=>$_GET['id_user']));
		}
	}
	$lim=(isset($_GET['limit_u']))?$_GET['limit_u']:0;
	$where='';
	if(isset($_POST['search']))
		$where=array('email'=>$_POST['search']);
	$data=$bd->select('*','users',$where,'',$lim.", 30");
	if($data){
	$s=sizeof($data);$i=0;
	echo'<table cellspacing="0" class="serv_tab">';
	echo'<tr><th>Email</th><th>Пароль</th><th>Бан</th><th>Тип</th><th></th><th>Кошелек</th><th>Баланс</th><th></th></tr>';
		while($i<$s){
			echo'<tr';
			if($i%2)echo' class="nochet" ';
			echo'><td>'.$data[$i]['email'].' '.(($data[$i]['activ'])?'+':'-').'</td>';
			echo'<td>'.$data[$i]['pass'].'</td>';
			echo'<td>';
			if($data[$i]['ban'])echo'<form action="'.$host.$_SERVER['REQUEST_URI'].'&id_user='.$data[$i]['id'].'" method="post"><input  name="ban" style="display:none;" value="0"/><input name="" class="button" type="submit" value="Разбан"></form>';
			else echo'<form action="'.$host.$_SERVER['REQUEST_URI'].'&id_user='.$data[$i]['id'].'" method="post"><input  name="ban"  style="display:none;" value="1"/><input name="" class="button" type="submit" value="Бан"></form>';
			echo'</td>';
			echo'<td>'.$data[$i]['type'].'</td>';
			echo'<td><form action="'.$host_lang.'/login.php" method="post"><input style="display:none;" name="email" value="'.$data[$i]['email'].'"/><input style="display:none;" name="pass" value="'.$data[$i]['pass'].'"/><input name="" class="button" type="submit" value="Войти"></form></td>';
			echo'<td>'.$data[$i]['purse'].'</td>';
			echo'<td><form action="'.$host.$_SERVER['REQUEST_URI'].'&id_user='.$data[$i]['id'].'" method="post"><input  name="balans" class="text_pole" style="width:100px;" value="'.$data[$i]['balans'].'"/><input name="" class="button" type="submit" value="Сохр"></form></td>';
			echo'<td><form action="'.$host.$_SERVER['REQUEST_URI'].'&id_user='.$data[$i]['id'].'" method="post"><input  name="del"  style="display:none;" value="1"/><input name="" class="button" type="submit" value="Удалить"></form></td></tr>';
			$i++;
		} 
		echo'</table>';
		if($lim)echo'<a href="'.$host_lang.'/adminka/index.php?a=user&limit_u='.($lim-30).'">пред</a>';
		echo' <a href="'.$host_lang.'/adminka/index.php?a=users&limit_u='.($lim+30).'">след</a>';
	}
?>