<?php 
$error='';
if(isset($_GET['server'])){
	include('scripts/bd.inc.php');		// подключение базы SQL
	$bd=new BD($user_bd,$pass_bd,$bd_custom,$host_bd);
	include('scripts/language.php');
	$content_buy=get_cont($lang,'buy_admin');
	$title=content('buy_priv',$content_buy);
	include("elements/head_buy.php");
	echo'<div class="content" style="width:800px;"><div id="buy_form"></div><div style="float:right;">';
	include('elements/languages.php');
	echo'</div><div style="clear:both;"></div>';
	$data_server=$bd->select('*','servers',array("id"=>$_GET['server']));
	$bd->delete_str('sess_buy',array('time'<time()));
	
	function buy_time_check(){
		global $bd;
		$time_ser=$bd->select(array('time_type','time_week','time_from','time_to','autobuy'),'privileges',array("id_server"=>$_GET['server']));
		if($time_ser[0]['autobuy']=='off')
			return false;
		if($time_ser[0]['time_type']=='alltime')
			return true;
		if($time_ser[0]['time_type']=='days'){
			$week=array('vs','pn','vt','sr','ch','pt','sb');
			$now_week=$week[date('w')];
			$week_ser=explode( ',', $time_ser[0]['time_week']);
			$week_ser=array_flip($week_ser);
			if(isset($week_ser[$now_week]))
				return true;
		}
		if($time_ser[0]['time_type']=='period')
			if(time()>$time_ser[0]['time_from']&&time()<$time_ser[0]['time_to'])
				return true;
		return false;
	}
	
	if(!$data_server) echo '<h3>',content('no_server',$content_buy),'</h3>';
	elseif(!buy_time_check($_GET['server'])){
		echo '<h3>',content('on_the_server1',$content_buy).' <b><span style="color:#000;">',$data_server[0]['name'],'</span></b> '.content('on_the_server2',$content_buy),'</h3>';
	}
	//1111111111111111111111111
	elseif(!isset($_GET['step'])){
		if(isset($_GET['sess_id'])){
			$dataForm=$bd->select('*','sess_buy',array('id'=>$_GET['sess_id']));
			$bd->delete_str('sess_buy',array('id'=>$_GET['sess_id']));
			$dataForm=unserialize($dataForm[0]['value']);
		}
		include("elements/form_buy_admin1.php");
	//2222222222222222222222
	}elseif($_GET['step']==2){
		function check_form($dataForm){
			global $error;
			global $content_buy;
			if(!isset($_POST['email'])){
				$error=content('email_need_to_cont',$content_buy);
				return false;
			}
			if(isset($_POST['ip'])&&$_POST['ip']!='')
				$dataForm['enter_type']='ip';
			elseif(isset($_POST['steam'])&&$_POST['steam']!='')
				$dataForm['enter_type']='steam';
			elseif(!isset($_POST['name'])||$_POST['name']==''){
				$error=content('should_be_ip_st_nick',$content_buy);
				return false;
			}else $dataForm['enter_type']='name';
			return true;
		}
		if(isset($_GET['go'])){
			$dataForm=$_POST;
			if(check_form($dataForm)){
				$id_sess=rand(0,10000000);
				$qsess=serialize($dataForm);
				$bd->insert('sess_buy',array('id'=>$id_sess,'value'=>$qsess,'time'=>time()+60*60*3));
				include("elements/form_buy_admin2.php");
			}else{
				include('elements/errors.php');
				include("elements/form_buy_admin1.php");
			}
		}
		if(isset($_GET['ch'])){
			$dataForm=$bd->select('*','sess_buy',array('id'=>$_GET['sess_id']));
			$dataForm=unserialize($dataForm[0]['value']);
			include("elements/form_buy_admin2.php");
		}
	//3333333333333333333333
	}elseif($_GET['step']==3){
		$summDay=0;
		function choose_flags($flags){
			global $summDay;
			global $error;
			global $content_buy;
			global $bd;$n=0;
			$arrF=array();
			if(isset($flags['offer'])){
				$offers=$bd->select(array('flags','price'),'offers',array('id'=>$flags['offer']));
				$summDay=$offers[0]['price'];
				$arrF=explode(',',$offers[0]['flags']);
				$n++;
			}else{
				$prices=$bd->select('*','privileges',array('id_server'=>$_GET['server']));
				$need=explode(',','a,b,c,d,e,f,g,h,i,j,g,k,l,m,n,o,p,q,r,s,t,u,z');
				foreach($need as $v){
					if(isset($flags[$v])){
						$summDay+=$prices[0][$v.'p'];
						$arrF[]=$v;
						$n++;
					}
				}
			}
			if(!$n){
				$error=content('no_privileges',$content_buy);
				return false;
			}
			if($flags['days']==''){
				$error=content('not_set_days',$content_buy);
				return false;
			}
			return $arrF;
		}
		
		$dataForm=$bd->select('*','sess_buy',array('id'=>$_GET['sess_id']));
		$dataForm=unserialize($dataForm[0]['value']);
		if($flags=choose_flags($_POST)){
			$dataForm['days']=$_POST['days'];
			$dataForm['summ_day']=$summDay;
			$dataForm['flags']=implode(',',$flags);
			$dataForm['id']=rand();
			$dataForm['id_server']=$_GET['server'];
			$bd->update('sess_buy',array('value'=>serialize($dataForm)),array('id'=>$_GET['sess_id']));
			$content_tr=get_cont($lang,'transactions');
			include("elements/form_buy_admin3.php");
		}else{
			include('elements/errors.php');
			include("elements/form_buy_admin2.php");
		}
	//4444444444444444444444
	}elseif($_GET['step']==4){
		$dataForm=$bd->select('*','sess_buy',array('id'=>$_GET['sess_id']));
		$dataForm=unserialize($dataForm[0]['value']);
		$bd->delete_str('sess_buy',array('id'=>$_GET['sess_id']));
		$id_user=$bd->select('id_user','servers',array('id'=>$dataForm['id_server']));
		$dataForm['id_user']=$id_user[0]['id_user'];
		$summDay=$dataForm['summ_day'];
		unset($dataForm['summ_day']);
		$bd->insert('admins',$dataForm);
		$coll=$bd->get_result($bd->sql_query("SELECT count(*) AS coll FROM `transactions`"));
		$content_tr=get_cont($lang,'transactions');
		$data=array(
			'id'=>$coll[0]['coll']+1,
			'direction'=>'adminka',
			'summ'=>$summDay*$dataForm['days'],
			'time'=>time(),
			'status'=>'no_paid',
			'desc'=>content('adminka',$content_tr).' '.$data_server[0]['name'],
			'id_user'=>$data_server[0]['id_user'],
			'id_adminka'=>$dataForm['id']
			//'sposob'=>$_POST['sposob']
		);
		$bd->insert('transactions',$data);
		header("Location: ".$host_lang.'/pays/index.php?a=pay&id='.$data['id']);
		//include("elements/form_buy_admin4.php");
	}
}
?></div>
</body>
</html>