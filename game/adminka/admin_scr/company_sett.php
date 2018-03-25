<?
include('../advertiser/add_banner_func.php');
if($_FILES&&check_files()){
	if(load_vtf($_GET['id'])){
		$resd=$bd->update('companies',array("activ"=>'on'),array('id'=>$_GET['id']));
		if($resd){
			$suc="Рекламная кампания успешно активирована";
			include('../elements/suc.php');
		}
		include('admin_scr/companies.php');
	}else{
		include('../elements/form_company1.php');
	}
}else{
	include('../elements/form_company1.php');
}
?>