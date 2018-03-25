<?php if(!isset($_POST['header'])||$_POST['header']==''){ ?><h1><?php echo $CONT->header;?></h1>
<?php }

    if(isset($_POST['header'])&&isset($_POST['text'])&&$_POST['header']!=''&&$_POST['text']!=''){
	$data = $_POST;
	$data['date'] = time();
        $data['id_user'] = $_SESSION['user']['id'];
	$res = $bd->insert_on_update('questions', $data);
	if($bd->error)echo $bd->error;
        include('g_elements/questions.php');
}elseif(isset($_SESSION['user']['id']))
    include 'g_elements/form_que.php';
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

