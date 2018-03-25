<?php
define("GROUP", 114913166); // группа для модерации
$optdb = array(
    'host'      => 'localhost',
    'port'      =>'3306',
    'user'      => 'a0016026_vktroll',
    'pass'      => "89@n&=N*rGlK7yB92^'E",
    'db'        => 'a0016026_vktroll',
    'charset'   => 'utf8'
    );

///  app standalone
$optvk_stnd = array(  
    'app_id'        => '5300766',
    'api_secret'    => 'OWBiww6wKzB0I1QoM9FG',
    'redirect_uri'  => 'http://vktrolls.sdaypdd.ru/auth.php'
);

/// app iframe/flash
$optvk_iframe = array(
    'app_id'        => '5192821',
    'api_secret'    => 'RgFHj2a4lwASbwnSYI1l',
    'redirect_uri'  => 'http://vktrolls.sdaypdd.ru/auth.php'
);