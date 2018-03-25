<?php
include ('scr/config.php');

include('scr/safemysql.class.php');
$db = new SafeMySQL($optdb);

include ('scr/vk.php');
$vk = new VK($optvk_stnd);

include ('scr/scopes.php');

if(!isset($_GET['code'])){
    $url = $vk->getAuthorizeUrl('groups,offline,notifications,email', $optvk_stnd['redirect_uri']);
    //echo $url;
    header ("Location: ".$url);
}else{
    $token = $vk->getAccessToken(true, $_GET['code']);
    if(is_admin($token)){
    $data = array(
        'key' => 'admin_token',
        'value' => $token,
        'time' => time());
    $sql  = "INSERT INTO `vars` SET ?u ON DUPLICATE KEY UPDATE ?u";
    
?>
<!DOCTYPE html>
<html>
    <head>
        <link href="scr/styles.css" rel="stylesheet" type="text/css" />
        <title>Враги народа</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script type="text/javascript" src="scr/jquery-2.1.3.min.js"></script>
        <script type="text/javascript" src="scr/jquery.querystring.js"></script>
        <script src="//vk.com/js/api/xd_connection.js?2"  type="text/javascript"></script>
        <script type="text/javascript" src="scr/trolls.js"></script>
        
        

    </head>
    <body>
        <?php echo'В базу добавлено админов ',$db->query($sql,$data,$data);?>
    </body>
</html>
<?php }}?>