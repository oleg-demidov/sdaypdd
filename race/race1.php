<h1><? echo $CONT->header;?></h1>
<? if(isset($_SESSION['user']['id'])){?>
<script type="text/javascript" src="<?php echo 'http://',$_SERVER['HTTP_HOST']?>/tests/scr/test.js" ></script>
<link rel="stylesheet" href="http://<?php echo $_SERVER['HTTP_HOST'];?>/tests/scr/test.css" type="text/css" media="screen" />
<script type="text/javascript" src="<?php echo 'http://',$_SERVER['HTTP_HOST']?>/race/scr/race_pdd.js" >
</script>
<script src="<?php echo 'http://',$_SERVER['HTTP_HOST']?>/race/scr/timer2/flipclock.min.js"></script>
<link href="<?php echo 'http://',$_SERVER['HTTP_HOST']?>/race/scr/timer2/flipclock.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" >
$('#head_img').css('background-image','url(<?php echo 'http://',$_SERVER['HTTP_HOST']?>/images/header2.png)');
$('body').ready(function(){
	opt_test = {
		'element' : 'test',
		'location' : '<?php echo 'http://',$_SERVER['HTTP_HOST']?>',
		'test_scr' : '/tests/scr/test.php',
		//'ans_scr' : '/tests/scr/ans.php',
		'img_dir' : '/tests/images/',
		'loc_back' : '/index.php?a=race&r=wins',
		<? if(isset($_SESSION['user']['type']))echo"'type' : '",$_SESSION['user']['type'],"',";?>
		'pause' : 1
	};
	
	opt_race = {
		'race' : 'race',
		'url_search':'http://<?php echo $_SERVER['HTTP_HOST']?>/race/scr/search.php',
		'url_start':'http://<?php echo $_SERVER['HTTP_HOST']?>/race/scr/start.php',
		'url_ans':'http://<?php echo $_SERVER['HTTP_HOST']?>/race/scr/ans.php',
		'url_in':'http://<?php echo $_SERVER['HTTP_HOST']?>/race/scr/in.php',
		'url_vibil':'http://<?php echo $_SERVER['HTTP_HOST']?>/race/images/vibil.png',
		'url_win':'http://<?php echo $_SERVER['HTTP_HOST']?>/race/scr/win.php',
		 <? if(isset($_SESSION['user']['id'])) echo "'id_user':".$_SESSION['user']['id'],','?>
		'cars_url': [
			'http://<?php echo $_SERVER['HTTP_HOST']?>/race/images/car1.png',
			'http://<?php echo $_SERVER['HTTP_HOST']?>/race/images/car2.png',
			'http://<?php echo $_SERVER['HTTP_HOST']?>/race/images/car3.png',
			'http://<?php echo $_SERVER['HTTP_HOST']?>/race/images/car4.png',
			'http://<?php echo $_SERVER['HTTP_HOST']?>/race/images/car5.png',
			'http://<?php echo $_SERVER['HTTP_HOST']?>/race/images/car6.png'
		],
		'cars_color':[
			'white','red','pink','blue','black','yellow'
		]
	};
	var race = new RACE(opt_race);
	race.init(new PDDTEST(opt_test));
});
</script>
<div id="race"></div>
<div id="test"></div>
<? }else echo '<div id="text">',stripslashes($CONT->data['text']),'</div>';?>

