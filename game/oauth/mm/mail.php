<?php

/**
 * The PHP class for vk.com API and to support OAuth.
 * @author Vlad Pronsky <vladkens@yandex.ru>
 * @license https://raw.github.com/vladkens/VK/master/LICENSE MIT
 * @version 0.1.6
 */
 
 
class MAIL
{
    /**
     * VK application id.
     * @var string
     */
    private $app_id;
    
    /**
     * VK application secret key.
     * @var string
     */
    private $api_secret;
	
	private $callback_url;
    
    /**
     * API version. If null uses latest version.
     * @var int
     */
    private $api_version;
    
    /**
     * VK access token.
     * @var string
     */
    private $access_token;
    
    /**
     * Authorization status.
     * @var bool
     */
    private $auth = false;
	
	private $auth_user_uid;
    
    /**
     * Instance curl.
     * @var resourse
     */
   // private $ch;
    
    const AUTHORIZE_URL    = 'https://connect.mail.ru/oauth/authorize';
    const ACCESS_TOKEN_URL = 'https://connect.mail.ru/oauth/token';
	private $API_URL = 'http://www.appsmail.ru/platform/api';

    /**
     * Constructor.
     * @param   string  $app_id
     * @param   string  $api_secret
     * @param   string  $access_token
     * @throws  VKException
     * @return  void
     */
    public function __construct($arr, $access_token = null)
    {
        $this->app_id       = $arr['id'];
        $this->api_secret   = $arr['secret'];
		$this->callback_url = $arr['callback'];
		$this->private_key  = $arr['private'];
        $this->access_token = $access_token;
        
        //$this->ch = curl_init();
        
       /* if (!is_null($this->access_token)) {
            if (!$this->checkAccessToken()) {
                throw new VKException('Invalide access token.');
            } else {
                $this->auth = true;
            }
        }*/
    }
    
    /**
     * Destructor.
     * @return  void
     */
   /* public function __destruct()
    {
        curl_close($this->ch);
    }*/
    
    /**
     * Set special API version.
     * @param   int     $version
     * @return  void
     */
    public function setApiVersion($version)
    {
        $this->api_version = $version;
    }
    
    /**
     * Returns base API url. 
     * @param   string  $method
     * @param   string  $response_format
     * @return  string
     */
    public function getApiUrl()
    {
        return $this->API_URL ;
    }
    
    /**
     * Returns authorization link with passed parameters.
     * @param   string  $api_settings
     * @param   string  $callback_url
     * @param   bool    $test_mode
     * @return  string
     */
    public function getAuthorizeUrl($callback_url, $test_mode = false)
    {
        $parameters = array(
            'client_id'     => $this->app_id,
			'response_type' => 'code',
            'redirect_uri'  => $this->callback_url
            
        );
        
                   
        return $this->createUrl(self::AUTHORIZE_URL, $parameters);
    }
    
	
	public function authRedirect(){
        header('Location: ' . $this->getAuthorizeUrl());
        exit();
	}
    /**
     * Returns access token by code received on authorization link.
     * @param   string  $code
     * @param   string  $callback_url
     * @throws  VKException
     * @return  array
     */
    public function getAccessToken($code, $callback_url = '')
    {
       /* if (!is_null($this->access_token) && $this->auth) {
            throw new VKException('Already authorized.');
        }*/
        
        $parameters = array(
            'client_id'     => $this->app_id,
            'client_secret' => $this->api_secret,
			'grant_type'	=> 'authorization_code',
            'code'          => $code,
            'redirect_uri'  => $this->callback_url
        );
        
        $rs = json_decode($this->request(
			$this->createUrl(self::ACCESS_TOKEN_URL),
			'POST',
			$parameters ));

        if (!isset($rs->error)) {
            $this->auth = true;
            $this->access_token = $rs->access_token;
			$this->auth_user_uid = $rs->x_mailru_vid;
			return $rs;
        }
    }
    
    /**
     * Return user authorization status.
     * @return  bool
     */
	public function getUserUid()
	{
		return $this->auth_user_uid;
	}
	
	public function getUserEmail()
	{
		return $this->auth_user_email;
	}
	
    public function isAuth()
    {
        return $this->auth;
    }
    
    /**
     * Check for validity access token.
     * @return  bool
     */
    private function checkAccessToken()
    {
        if (is_null($this->access_token)) return false;
        
        $rs = $this->api('getUserSettings');
        return isset($rs['response']);
    }
    
    /**
     * Execute API method with parameters and return result.
     * @param   string  $method
     * @param   array   $parameters
     * @param   string  $format
     * @param   string  $requestMethod
     * @return  mixed
     */
    public function api($method, $parameters = array(), $requestMethod = 'get')
    {
        $parameters['method'] = $method;
        $parameters['api_id']    = $this->app_id;
        $parameters['session_key'] = $this->access_token;
        
        $sig = '';
        foreach ($parameters as $key => $value) {
            $sig .= $key . '=' . $value;
        }
        $sig .= $this->api_secret;
        
        $parameters['sig'] = $this->sign_client_server(
				$parameters, 
				$this->auth_user_uid, 
				$this->private_key);
        $rs = $this->request($this->getApiUrl(), "POST", $parameters);
       
        return json_decode($rs);
    }
	
	
    public function sign_client_server(array $request_params, $uid, $private_key) {
		ksort($request_params);
		$params = '';
		foreach ($request_params as $key => $value) {
			$params .= "$key=$value";
		}
		return md5($uid . $params . $private_key);
	}
    /**
     * Concatinate keys and values to url format and return url.
     * @param   string  $url
     * @param   array   $parameters
     * @return  string
     */
    private function createUrl($url, $parameters = array())
    {
        $url .= '?' . http_build_query($parameters);
        return $url;
    }
    
    /**
     * Executes request on link.
     * @param   string  $url
     * @param   string  $method
     * @param   array   $postfields
     * @return  string
     */
    private function request($url, $method = 'GET', $postfields = array())
    {
        curl_setopt_array($curl = curl_init(), array(
            CURLOPT_USERAGENT       => 'PDD',
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_SSL_VERIFYPEER  => false,
            CURLOPT_POST            => ($method == 'POST'),
            CURLOPT_POSTFIELDS      => $postfields,
            CURLOPT_URL             => $url
        ));
        $data = curl_exec($curl);
		curl_close($curl);
        return  $data;
    }
    
};
    
