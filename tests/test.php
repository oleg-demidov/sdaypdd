<script type="text/javascript" src="<?php echo 'http://',$_SERVER['HTTP_HOST']?>/scr/pdd_punkts.js" ></script>
<div class="overlay" title="Закрыть"></div>
<div class="popup">
</div>
<script type="text/javascript" src="<?php echo 'http://',$_SERVER['HTTP_HOST']?>/tests/scr/test.js" ></script>
<script type="text/javascript" >
$('body').ready(start);
function start(){
	opt = {
		'element' : 'test',
		'location' : '<?php echo 'http://',$_SERVER['HTTP_HOST']?>',
		'test_scr' : '/tests/scr/test.php',
		'ans_scr' : '/tests/scr/ans.php',
		'img_dir' : '/tests/images/',
	<?php	
                echo"'length' : ",isset($_GET['length'])?$_GET['length']:0,",";
		if(isset($_GET['comm']))echo"'comments' : ",$_GET['comm'],",";
		if(isset($_SESSION['user']['type']))echo"'type' : '",$_SESSION['user']['type'],"',";
		if(isset($_SESSION['user']['id'])) echo"'user_id' : '".$_SESSION['user']['id']."',";
		$back_str = '';
		if(isset($_GET['tema'])){
			$func_str ='tema('.$_GET['tema'].');';
			$back_str .= "'loc_back' : '/index.php?a=obuchenie&o=tems',";
		}
		if(isset($_GET['bilet'])){
		$func_str ='bilet('.$_GET['bilet'].');';
		$back_str .= "'loc_back' : '/index.php?a=obuchenie&o=bilets',";
		}
		if(isset($_GET['errors'])){
		$func_str ='false();';
		$back_str .= "'loc_back' : '/index.php?a=obuchenie&o=errors',";
		}
		echo $back_str;
	?>
		'pause' : 1
	};
	var TEST = new PDDTEST(opt);
	TEST.get_<? echo $func_str;?>
}	
</script>
<div class="info"></div>
<div id="test"></div>
<?php include 'g_elements/adsblock720_90.php';?>