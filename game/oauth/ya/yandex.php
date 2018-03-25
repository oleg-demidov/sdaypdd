<?php
/**
 * Yandex PHP Library
 *
 * @copyright NIX Solutions Ltd.
 * @link https://github.com/nixsolutions/yandex-php-library
 */

/**
 * @namespace
 
namespace Yandex\OAuth;

use Guzzle\Http\Client;
use Guzzle\Http\Exception\ClientErrorResponseException;
use Guzzle\Http\Exception\RequestException;
use Yandex\OAuth\Exception\AuthRequestException;
use Yandex\OAuth\Exception\AuthResponseException;
*/
/**
 * Class OAuthClient implements Yandex OAuth protocol
 *
 * @category Yandex
 * @package  OAuth
 *
 * @author   Eugene Zabolotniy <realbaziak@gmail.com>
 * @created  29.08.13 12:07
 */
class OAuthClient
{
    /*
     * Authentication types constants
     *
     * The "code" type means that the application will use an intermediate code to obtain an access token.
     * The "token" type will result a user is redirected back to the application with an access token in a URL
     */
    const CODE_AUTH_TYPE = 'code';
    const TOKEN_AUTH_TYPE = 'token';

    /**
     * @var string
     */
    private $clientId = '';

    /**
     * @var string
     */
    private $clientSecret = '';

    /**
     * @var string
     */
    protected $serviceScheme = 'https';

    /**
     * @var string
     */
    protected $serviceDomain = 'oauth.yandex.ru';
	
	protected $passportDomain = 'login.yandex.ru/info';

    /**
     * @var string
     */
    protected $servicePort = '';

    /**
     * @var string
     */
    protected $accessToken = '';

    /**
     * @param string $clientId
     * @param string $clientSecret
     */
    public function __construct($clientId = '', $clientSecret = '')
    {
        $this->setClientId($clientId);
        $this->setClientSecret($clientSecret);
    }

    /**
     * @param string $clientId
     *
     * @return self
     */
    public function setClientId($clientId)
    {
        $this->clientId = $clientId;

        return $this;
    }

    /**
     * @return string
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * @param string $clientSecret
     *
     * @return self
     */
    public function setClientSecret($clientSecret)
    {
        $this->clientSecret = $clientSecret;

        return $this;
    }

    /**
     * @return string
     */
    public function getClientSecret()
    {
        return $this->clientSecret;
    }

    /**
     * @param string $accessToken
     *
     * @return self
     */
    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    /**
     * @return string
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * @param string $serviceDomain
     *
     * @return self
     */
    public function setServiceDomain($serviceDomain)
    {
        $this->serviceDomain = $serviceDomain;

        return $this;
    }

    /**
     * @return string
     */
    public function getServiceDomain()
    {
        return $this->serviceDomain;
    }

    /**
     * @param string $servicePort
     *
     * @return self
     */
    public function setServicePort($servicePort)
    {
        $this->servicePort = $servicePort;

        return $this;
    }

    /**
     * @return string
     */
    public function getServicePort()
    {
        return $this->servicePort;
    }

    /**
     * @param string $serviceScheme
     *
     * @return self
     */
    public function setServiceScheme($serviceScheme)
    {
        $this->serviceScheme = $serviceScheme;

        return $this;
    }

    /**
     * @return string
     */
    public function getServiceScheme()
    {
        return $this->serviceScheme;
    }

    /**
     * @param string $resource
     * @return string
     */
    public function getServiceUrl($resource = '')
    {
        return $this->serviceScheme . '://' . $this->serviceDomain . '/' . rawurlencode($resource);
    }
	
	 public function getPassportUrl($resource = '')
    {
        return $this->serviceScheme . '://' . $this->passportDomain . '/' . rawurlencode($resource);
    }
    /**
     * @param string $type
     * @param string $state optional string
     *
     * @return string
     */
    public function getAuthUrl($type = self::CODE_AUTH_TYPE, $state = null)
    {
        $url = $this->getServiceUrl('authorize') . '?response_type=' . $type . '&client_id=' . $this->clientId;
        if ($state) {
            $url .= '&state=' . $state;
        }

        return $url;
    }

    /**
     * Sends a redirect to the Yandex authentication page.
     *
     * @param bool   $exit  indicates whether to stop the PHP script immediately or not
     * @param string $type  a type of the authentication procedure
     * @param string $state optional string
     */
    public function authRedirect($exit = true, $type = self::CODE_AUTH_TYPE, $state = null)
    {
        header('Location: ' . $this->getAuthUrl($type, $state));

        if ($exit) {
            exit();
        }
    }

    /**
     * Exchanges a temporary code for an access token.
     *
     * @param $code
     *
     * @throws AuthRequestException on a known request error
     * @throws AuthResponseException on a response format error
     * @throws RequestException on an unknown request error
     *
     * @return self
     */
	private function request($url, $method = 'GET', $postfields = array())
    {
		$curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_USERAGENT       => 'VK/1.0 (+https://github.com/vladkens/VK))',
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_SSL_VERIFYPEER  => false,
            CURLOPT_POST            => ($method == 'POST'),
            CURLOPT_POSTFIELDS      => $postfields,
            CURLOPT_URL             => $url
        ));
        return curl_exec($curl);
    }

	
	public function getUserInfo(){
		$request = $this->request(
			$this->getPassportUrl(),
			'GET',
			array(
                'format'    => 'json',
                'oauth_token'          => $this->getAccessToken()
        ));
		return json_decode($request);
	}
	
	
    public function requestAccessToken($code)
    {
        $request = $this->request(
			$this->getServiceUrl('token'),
			'POST',
			array(
                'grant_type'    => 'authorization_code',
                'code'          => $code,
                'client_id'     => $this->clientId,
                'client_secret' => $this->clientSecret
        ));
		$result = json_decode($request);
		$this->setAccessToken($result->access_token);

        return $this;
    }
}
