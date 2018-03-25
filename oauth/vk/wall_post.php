<?php
session_start();
if(isset($_SESSION['share_url']) && isset($_SESSION['user']['id']) && isset($_GET['back'])){
    include('../../scr/bd.inc.php');		// подключение базы SQL
    $bd = new BD($user_bd, $pass_bd, $bd, $host_bd);
    
    $res = sendRequest('https://vk.com/share.php?act=count&url='.$_SESSION['share_url']);
    $res = parseCount($res);
    
    if($res && $_SESSION['share_count']<$res){
        //echo 'upd';
        $bd->sql("UPDATE `social` SET `post`='yes' WHERE `soc`='vk' AND `id_user`=?", array($_SESSION['user']['id']));
    }
    unset($_SESSION['share_url']);
    unset($_SESSION['share_count']);
    //echo $_GET['back'];
    header("Location: ".$_GET['back']);
    
    exit();
}
if(isset($_GET['back'])){
    header("Location: ".$_GET['back']);
    exit();
}
$pars = json_decode(urldecode($_GET['pars']));
//print_r($pars);

$params = [
    'url' => $pars->url,
    'title' => $pars->header,
    //'description' => $pars->text,
    'image' => $pars->image
];
//print_r($params);
$res = sendRequest('https://vk.com/share.php?act=count&url='.$pars->url);
$_SESSION['share_url'] = $pars->url;
$_SESSION['share_count'] = parseCount($res);//
//print_r($_SESSION['share_count']);

$query = http_build_query($params);
//echo $query;
header("Location: https://vk.com/share.php?".$query);

function parseCount($string){
    $arr = spliti("\([0-9]+,.?|\);",$string );
    if(!$arr) return false;
    return $arr[1];
}

function sendRequest($url = '', $params = array(), $method = 'POST') {
        if(is_array($params)) {
                $params = http_build_query($params);
        }
        $ch = curl_init();
        if($method == 'GET') {
                $url .= $params;
        } else if($method == 'POST') {
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
}
/*session_start();
$app = array(
            'client_id' => '1132797440',
            'application_key' => 'CBAPBGIEEBABABABA',
            'client_secret' => '874877CAFF43BB7F842C3F1C'
    );

function widgetOpen($widget, $attach, $returnUrl) {
    global $app;
    
     $query = 'https://connect.ok.ru/dk?st.cmd='. $widget . '&st.app=' . $app['client_id'];
    
    $sigSource = 'st.attachment=' . base64_encode($attach);
    $sigSource .= 'st.return=' . $returnUrl;
    
    $query .= '&st.attachment=' . base64_encode($attach);
    $query .= '&st.return=' . rawurlencode($returnUrl);
    
    $sigSource .= $app['client_secret'];//'874877CAFF43BB7F842C3F1C'
    echo $sigSource.'<br>';    
    
    $query .= '&st.signature=' . md5($sigSource);
    //$query .= '&st.access_token=' . $_SESSION['user']['token'];

    echo $query.'<br>';
    }
widgetOpen('WidgetMediatopicPost', '{"media":[{"type":"link","url":"http://sdaypdd.ru/index.php?a=obuchenie"}]}', urldecode($_GET['return']));
    
include('odnoklassniki.php');
$ok = new Social_APIClient_Odnoklassniki(  $app );
$ok->setAccessToken($_SESSION['user']['token']);
print_r($_SESSION);*/

?>