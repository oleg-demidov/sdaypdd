<h1><?php echo $CONT->header;?></h1>
<?php if(isset($_SESSION['user']['id'])){?>
<?php //print_r($_SESSION['user']); ?>
<script type="text/javascript" src="<?php echo 'http://',$_SERVER['HTTP_HOST']?>/tests/scr/test.js" ></script>
<link rel="stylesheet" href="http://<?php echo $_SERVER['HTTP_HOST'];?>/tests/scr/test.css" type="text/css" media="screen" />
<script type="text/javascript" src="<?php echo 'http://',$_SERVER['HTTP_HOST']?>/race/scr/race.js" ></script>
<script src="<?php echo 'http://',$_SERVER['HTTP_HOST']?>/race/scr/timer2/flipclock.min.js"></script>
<link href="<?php echo 'http://',$_SERVER['HTTP_HOST']?>/race/scr/timer2/flipclock.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" >
var race;
$('#head_img').css('background-image','url(<?php echo 'http://',$_SERVER['HTTP_HOST']?>/images/header2.png)');
$('body').ready(function(){
	opt_test = {
            'element' : 'test',
            'location' : '<?php echo 'http://',$_SERVER['HTTP_HOST']?>',
            'test_scr' : '/tests/scr/test.php',
            //'ans_scr' : '/tests/scr/ans.php',
            'img_dir' : '/tests/images/',
            'loc_back' : '/index.php?a=race&r=wins',
            <?php if(isset($_SESSION['user']['type']))echo"'type' : '",$_SESSION['user']['type'],"',";?>
            <?php if(isset($_SESSION['user']['type']))echo"'soc' : '",$_SESSION['user']['type'],"',";?> 
            'pause' : 1
	};
	
	opt_race = {
            'test' : new PDDTEST(opt_test),
            'race' : 'race',
            'url_race':'http://<?php echo $_SERVER['HTTP_HOST']?>/race/scr/race.php',
            'url_start':'http://<?php echo $_SERVER['HTTP_HOST']?>/race/scr/start.php',
            'url_ans':'http://<?php echo $_SERVER['HTTP_HOST']?>/race/scr/ans.php',
            'url_in':'http://<?php echo $_SERVER['HTTP_HOST']?>/race/scr/in.php',
            'url_vibil':'http://<?php echo $_SERVER['HTTP_HOST']?>/race/images/vibil.png',
            'url_win':'http://<?php echo $_SERVER['HTTP_HOST']?>/race/scr/win.php',
             <?php if(isset($_SESSION['user']['id'])) echo "'id_user':".$_SESSION['user']['id'],','?>
             <?php if(isset($_SESSION['user']['gost'])) echo "'gost':1,"?>
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
	race = new RACE(opt_race);
});
</script>
<div id="banner_race">
    <img alt="Гонки по правилам ПДД" style="width: 100%;" src="/race/images/banner.jpg"/>
    <p>
        Правила игры:
    <ul>
        <li>В гонках могут принять участие одновременно 4 игрока.</li>
        <li>Игрок считается выбывшым, если он не отвечал на вопросы в течении 60 секунд.</li>
        <li>За неправильный ответ, штраф - 10 секунд простоя.</li>
        <li>Пользователь не может принять участие в новой гонке до тех пор, пока не завершилась текущая.</li>
    </ul>
    </p>
    <div id="rbtns">
        <table align="center"  ><tr><td align="center" cellpadding="0" cellspacing="0">
        <script src="//yastatic.net/es5-shims/0.0.2/es5-shims.min.js"></script>
        <script src="//yastatic.net/share2/share.js"></script>
        <div class="ya-share2" data-title="Гонки по правилам ПДД" data-image="http://sdaypdd.ru/images/logo128.jpg" data-description="<?php echo $_SESSION['user']['name']?> приглашает поиграть в Гонки по правилам" data-services="vkontakte,facebook,odnoklassniki,moimir,gplus,twitter"></div>
                </td></tr></table>
    </div>
    
</div>
<div id="racers"></div>
<div id="but">
    <div class="racer radius start_div">
    </div>
</div>
<div id="cars">
    <div class="car100" id="car101"></div>
    <div class="car100" id="car102"></div>
    <div class="car100" id="car103"></div>
    <div class="car100" id="car104"></div>
</div>

<div id="test"></div>
<?php include 'g_elements/adsblock720_90.php';?>
<?php }else echo '<div id="text">',stripslashes($CONT->data['text']),'</div>';?>


