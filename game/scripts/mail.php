<?
$email='boxmilo@gmail.com';
$subject = "Добро пожаловать ";
	$message = " <html><head><title>Добро пожаловать на сайт pddrk.kz!</title></head> 
				<body> <h4>Добро пожаловать на сайт pddrk.kz!  </h4>
				<p>Вот ваши данные для входа:<br>Логин:  <br/>Пароль:</p> 
				<p>Сохраните это письмо на всякий пожарный.<br>Удачи в изучении правил и на экзамене!</p>
				</body></html>";
	$headers = "From: PDDRK.KZ <kontakt@pddrk.kz>\r\n";
    $headers .= "Content-type: text/html; charset=utf-8 \r\n";
	mail($email, $subject,$message, $headers);
?>