<?

$url_reset=$host_lang."/remember_pass.php?kod=".$kod;
$subj="Cs-money.net ".content('save_pass',$content_sp);
$text=content('email_text_reset_pass',$content_sp);
$text=str_replace("%id_user", $id_user[0]['name'], $text);
$text=str_replace("%url_reset", $url_reset, $text);
$headers  = "Content-type: text/html; charset=utf-8 \r\n"; 
$headers .= "From: support@cs-money.net \r\n"; 
if(mail($id_user[0]['email'], $subj, $text, $headers))
$suc=content('on_email_send',$content_sp);
include('elements/suc.php');
?>
