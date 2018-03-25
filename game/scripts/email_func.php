<?
function send_mail($mail, $subj, $text){
	$headers  = "Content-type: text/html; charset=utf-8 \r\n"; 
	$headers .= "From: support@cs-money.net \r\n"; 
	return mail($mail, $subj, $text, $headers);
}

?>
