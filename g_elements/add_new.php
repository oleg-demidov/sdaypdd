<h1><? echo $CONT->header;?></h1>
<?
if(isset($_GET['id'])){
	$data = $bd->get_row("SELECT * FROM `pages` WHERE `id`=?",array($_GET['id']));
	$data['text'] = stripslashes($data['text']);
        //$data['seotext'] = stripslashes($data['seotext']);
}
//print_r($_POST);
if(isset($_POST['title'])){
	$data = $_POST;
	$data['data'] = time();
        $data['type'] = 'news';
        $data['text'] = str_replace("\r\n", " ", $data['text']);
        $data['seotext'] = str_replace("\r\n", " ", $data['seotext']);
	$bd->insert_on_update('pages', $data);
	if($bd->error)echo $bd->error;
	include('news.php');
}else
	include('form_new.php');
?>