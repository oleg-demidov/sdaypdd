<?php

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
        return json_decode($result, true);
}

