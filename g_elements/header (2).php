<body>
<? 
$index = $bd->get_row("SELECT `value` AS `index` FROM `settings` WHERE `name`='home'");
$collus = $bd->get_row("SELECT count(*) as `coll` FROM `users`");
?>
<div class="gcanvas">
		<div class="title"><a  href="http://<? echo $hh?>/index.php?a=page&id=<? echo $index['index'];?>">
Правила дорожного движения</a></div>
		<div class="header">
		  <div class="logo"><a title="Главная <? echo $collus['coll']?>" href="http://<? echo $hh?>"> </a></div>
		  <div class="menu">
			<div class="menu_polosi">&#8801</div>
			<script type="text/javascript">
			var to;
			var urlsWidth;
			var hider = false;
			$( window ).load(check_urls);
			$(document).ready(function(){
				$('.menu_polosi').click(function(){
					$('.urls').css('left',0);
					to = setTimeout(function(){$('.urls').css('left','-400px');},5000);
				});
				$('.content').click(function(){
					$('.urls').css('left','-400px');
					clearTimeout(to);
				});
				$( window ).resize(function() { check_urls();});     
                                setTimeout(function(){$('.panel').show();},1000)
			});
			function hide_atext(){
				hider = true;
				$('.urls ul a').map(function(indx, element){
					if($(element).html()!='')
						$(element).attr('htm',$(element).html());
					$(element).html('');
				});
			}
			function show_atext(){
				hider = false;
				$('.urls ul a').map(function(indx, element){
					$(element).html($(element).attr('htm'));
				});
			}
			function check_urls(){
				if($( '.header' ).width() < 480){
					show_atext()
					return;
				}
				if(!hider)	urlsWidth = $('.urls ul').width()+5;
				//console.log($('.urls ul').width()+' '+($( '.header' ).width()-$('.enter_btn').width()))
				if(urlsWidth > ($( '.header' ).width()-$('.enter_btn').width()))
					hide_atext();
				else show_atext();
			}
			</script>
			<div class="urls"><ul>

<?
$menu = $bd->get_all("SELECT `pages`.`name`, `pages`.`id`, `pages`.`type`, `pages`.`title`, `menu`.`num`,`menu`.`class`, `menu`.`text` FROM `menu` LEFT JOIN `pages` ON `pages`.`id`=`menu`.`id_page` ORDER BY `menu`.`num`");
foreach($menu as $coll){
	?><li><a title="<? echo $coll['title'];?>" class="<? echo $coll['class'];?>" href="http://<? echo $hh?>/index.php?a=<? if($coll['type'] == 'page') echo 'page'; else echo $coll['name'];?><? if($coll['type'] == 'page') echo'&id=',$coll['id'];?>"><? echo $coll['text'];?></a></li><?
}

?>
			</ul>
			</div>
			
			<div class="<? if(isset($_SESSION['user']['id']))echo'prfl_btn';?> enter_btn"><a href="http://<? echo $hh?>/index.php?a=login"><? if(isset($_SESSION['user']['id']))echo'ПРОФИЛЬ'; else echo'ВХОД';?></a></div>
			
			
			<div style="clear:both;"></div>
		  </div>
		  <div class="breadcrumbs">
			 <?  echo breadcrumbs($CONT->title);?>
		  </div>
		</div>
	    
		<div class="panel">
		 	<? include ('panels.php');?>
		</div>