<?php

class VK
{
    private $app_id;
    private $api_secret;
    private $api_version = '5.42';
    private $access_token;
    private $redirect_uri;
    private $auth = false;
    private $serverAuth = false;
    var $error;
	
    private $auth_user_uid;
    private $ch;
    var $userAuthData;
    
    const AUTHORIZE_URL    = 'https://oauth.vk.com/authorize';
    const ACCESS_TOKEN_URL = 'https://oauth.vk.com/access_token';

    public function __construct( $opt /*$app_id, $api_secret, $access_token = false*/)
    {
        if(isset($opt['app_id']))       $this->app_id = $opt['app_id'];
        if(isset($opt['api_secret']))   $this->api_secret   = $opt['api_secret'];
        if(isset($opt['access_token'])) $this->access_token = $opt['access_token'];
        if(isset($opt['redirect_uri'])) $this->redirect_uri = $opt['redirect_uri'];
        //echo(json_encode($opt));
        $this->ch = curl_init();
        
    }
    
    public function __destruct()
    {
        curl_close($this->ch);
    }
    
    public function setServerToken($token)
    {
        $this->access_token = $token;
        $this->serverAuth = true;
    }
    
    public function setUserToken($token)
    {
        $this->access_token = $token;
        $this->serverAuth = false;
    }
    
    public function getAuthorizeUrl($api_settings = '',
        $callback_url = 'https://api.vk.com/blank.html', $test_mode = false)
    {
        $parameters = array(
            'client_id'     => $this->app_id,
            'scope'         => $api_settings,
            'redirect_uri'  => $callback_url,
            'response_type' => 'code',
            'v'             => $this->api_version
        );
        
        if ($test_mode)
            $parameters['test_mode'] = 1;
            
        return $this->createUrl(self::AUTHORIZE_URL, $parameters);
    }
    
    public function getAccessToken($userAuth = false, $code = '')
    {
        //echo' getAccessToken';
        $parameters = array(
            'client_id'     => $this->app_id,
            'client_secret' => $this->api_secret,
            'v'             => $this->api_version
        );
        //echo(json_encode($parameters));
        if($userAuth){
            $parameters['redirect_uri'] = $this->redirect_uri;
            $parameters['code'] = $code;
        }else{
            $parameters['grant_type'] = 'client_credentials';
        }
        
        $rs = json_decode($this->request(
            $this->createUrl(self::ACCESS_TOKEN_URL, $parameters)), true);
        //print_r($rs);
        if (isset($rs['error'])) {
            $this->error = $rs['error']. $rs['error_description'];
            return false;
        } else {
            $this->userAuthData = $rs;
            $this->auth = true;
            $this->access_token = $rs['access_token'];
            $this->serverAuth = true;
            return $this->access_token;
        }
        
		
    }
    
    private function checkUserAccessToken()
    {
        if ($this->access_token) return false;
        
        $rs = $this->api('getUserSettings');
        return isset($rs['response']);
    }
    
    private function checkServerAccessToken()
    {
        if ($this->access_token) return false;
        
        $data = $vk->api("secure.checkToken", array('token'=>$opt['access_token']));
        return isset($data['response']);
    }
    
    public function api($method, $parameters = array())
    {
        //echo 2;
        if($this->serverAuth)
            $parameters['client_secret'] = $this->api_secret;
        $parameters['access_token'] = $this->access_token;
        $rs = $this->request(
                $this->createUrl( $this->getApiUrl($method), $parameters)
                );
        return json_decode($rs, true) ;
    }
    
    public function getApiUrl($method, $response_format = 'json')
    {
        return 'https://api.vk.com/method/' . $method . '.' . $response_format;
    }
    
    private function createUrl($url, $parameters)
    {
        $url .= '?' . http_build_query($parameters);
        return $url;
    }
    
    public function request($url, $method = 'GET', $postfields = array())
    {
        //echo $url,'<br>';
        curl_setopt_array($this->ch, array(
            CURLOPT_USERAGENT       => 'VK/1.0 (+https://github.com/vladkens/VK))',
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_SSL_VERIFYPEER  => false,
            CURLOPT_POST            => ($method == 'POST'),
            CURLOPT_POSTFIELDS      => $postfields,
            CURLOPT_URL             => $url
        ));
        return curl_exec($this->ch);
    }
    
};
    
