<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="icon" href="<? echo $host;?>/images/favicon.png" type="image/x-icon" />
<title><?php echo $title;?></title>
<link href="<? echo $host;?>/global.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<? echo $host;?>/scripts/jquery-1.8.3.min.js"></script>
</head>
<body>
<div id="head">
<?

include('languages.php');

if(isset($_SESSION['id']))
	echo $_SESSION['email'].' <a href="'.$host_lang.'/login.php?logout=1">',content('exit'),'</a>';
else echo '<a id="a_login" href="'.$host_lang.'/login.php">',content('enter'),'</a>&emsp;<a id="a_reg" href="'.$host_lang.'/registration.php">',content('registration'),'</a>';
?>

</div>
