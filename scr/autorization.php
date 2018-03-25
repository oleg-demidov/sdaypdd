<?php
include('auth_func.php');
if(check_hash($_SESSION))write_hash($_SESSION);
else header("Location: http://".$_SERVER['HTTP_HOST']."/login.php?logout=1");

?>