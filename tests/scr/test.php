<?php
include('classes_test.php');
include('../../scr/pdd_sort.php');
$pddsort = new PDDSORT($bd);
$data = array();
if(isset($_GET['num'])){
	if(isset($_GET['bilet'])){
		if(isset($_GET['type']))
			$data = get_que_bilet($_GET['bilet'], $_GET['num'], $_GET['type']);
		else $data = get_que_bilet($_GET['bilet'], $_GET['num']);
	}
	if(isset($_GET['tema'])){
		if(isset($_GET['type']))
			$data = get_que_tema($_GET['tema'], $_GET['num'], $_GET['type']);
		else $data = get_que_tema($_GET['tema'], $_GET['num']);
	}
	if(isset($_GET['false'])){
		if(isset($_GET['type']))
			$data = get_que_false($_GET['id_user'], $_GET['num'], $_GET['type']);
		else $data = get_que_false($_GET['id_user'], $_GET['num']);
	}
	if(isset($_GET['random'])){
		if(isset($_GET['type']))
			$data = get_que_random($_GET['type']);
		else $data = get_que_random();
	}
	if(isset($_GET['number'])){
		if(isset($_GET['type']))
			$data = get_number($_GET['number'], $_GET['type']);
		else $data = get_number($_GET['number']);
	}
}
if(isset($_GET['ustat'])){
    if(isset($_GET['bilet'])){
        $data = get_stat_bilet($_GET['bilet'], $_GET['ustat'], $_GET['type']);
    }
    if(isset($_GET['tema'])){
        $data = get_stat_tems($_GET['tema'], $_GET['ustat'], $_GET['type']);
    }
}
if($data)$data['comm'] = $pddsort->add_href(stripslashes($data['comm']));
echo json_encode($data);
?>