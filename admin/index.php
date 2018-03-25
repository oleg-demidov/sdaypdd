<? 
	include('../scr/classes.php');
	include('scr/functions.php');
	$tits = array(
		'settings'=>'Настройки',
		'timgs'=>'Изображения',
		'timg_del'=>'Удалть изображение',
		'timg'=>'Изображение редактировать',
		'users'=>'Пользователи',
		'user_del'=>'Пользователь редактировать',
		'user'=>'Пользователь',
		'tests'=>'Тесты',
		'test_del'=>'Тесты',
		'test'=>'Тесты',
		'signs'=>'Знаки',
		'sign_del'=>'Удаление знака',
		'sign'=>'Редактировать знак',
		'pdd'=>'Редактирование пдд',
		'pdd_del'=>'Редактирование пдд',
		'pdds'=>'Редактирование пдд',
		'page'=>'Редактирование страницы',
		'page_del'=>'Редактирование страницы',
		'pages'=>'Редактирование страницы',
		'categories'=>'Категории',
		'cat_signs'=>'Категории знаков',
		'menu'=>'Меню'
	);
	$dir = get_dir('pdds', 'scr');
	$title='Админка '.$tits[$dir];
	include('elements/head.php');
	include('elements/menu.php');
?>
<div class="content"><?	include('scr/'.$dir.'.php');?></div>
</body>
</html>
