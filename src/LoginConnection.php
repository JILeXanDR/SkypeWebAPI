<?php

namespace WebSkype;

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Cookie\SetCookie;
use Psr\Http\Message\ResponseInterface;
use WebSkype\Session\SessionStorageInterface;

/**
 * Класс авторизации пользователя на сайте http://skype.web.com
 * Class LoginConnection
 * @package WebSkype
 */
class LoginConnection
{
    const WEB_BASE_URL = 'https://web.skype.com';
    const API_BASE_URL = 'https://api.skype.com';
    const CLIENT_ID = 578134;

    private $client;

    private $sessionTokenStorage;

    /**
     * LoginConnection constructor.
     * @param SessionStorageInterface $sessionTokenStorage
     */
    public function __construct(SessionStorageInterface $sessionTokenStorage)
    {
        $this->sessionTokenStorage = $sessionTokenStorage;
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        if (is_null($this->client)) {
            $this->client = new Client(['base_uri' => static::API_BASE_URL]);
        }

        return $this->client;
    }

    /**
     * @param $html
     * @param $name
     * @return null
     */
    private function getFieldValue($html, $name)
    {
        $re = 'name="' . $name . '" .* value="(.{128})"';

        preg_match('/' . $re . '/', $html, $matches);

        return isset($matches[1]) ? $matches[1] : null;
    }

    /**
     * @param $username
     * @param $password
     * @return SessionStorageInterface
     */
    public function login($username, $password)
    {
        $refreshToken = $this->gettingRefreshToken($username, $password);
        $XSkypeToken = $this->gettingXSkypeToken($refreshToken);
        $registrationToken = $this->gettingRegistrationToken();

        if (!$XSkypeToken || !$registrationToken) {
            throw new \InvalidArgumentException('Не удалось получить "X-SkypeToken" или "RegistrationToken"');
        }

        return $this->sessionTokenStorage
            ->setRefreshToken($refreshToken)
            ->setRegistrationToken($registrationToken)
            ->setXSkypeToken($XSkypeToken);
    }

    /**
     * @param $username
     * @param $password
     * @return string
     */
    private function gettingRefreshToken($username, $password)
    {
        $clientID = static::CLIENT_ID;
        $redirectURI = static::WEB_BASE_URL;
        $url = "https://login.skype.com/login?client_id={$clientID}&redirect_uri={$redirectURI}";

        $loginPageResponse = $this->getClient()->get($url);
        $loginPageHTML = $loginPageResponse->getBody()->getContents();

        $formHiddenValues = (object)[
            'pie' => $this->getFieldValue($loginPageHTML, 'pie'),
            'etm' => $this->getFieldValue($loginPageHTML, 'etm')
        ];

        $bodyParams = [
            'username' => $username,
            'password' => $password,
            'pie' => $formHiddenValues->pie,
            'etm' => $formHiddenValues->etm,
            'client_id' => $clientID,
            'timezone_field' => '+02|00',
            'persistent' => 1,
            'js_time' => time()
        ];

        $response = $this->getClient()->post($url, [
            'body' => json_encode($bodyParams)
        ]);;

        $refreshToken = $this->parseRefreshToken($response);

        if ($refreshToken) {
            Logger::append(sprintf("Получен 'RefreshToken' => %s...", substr($refreshToken, 0, 100)));
        } else {
            throw new \LogicException(sprintf("Не удалось получить 'RefreshToken' => %s...", $response->getBody()->getContents()));
        }

        return $refreshToken;
    }

    /**
     * @return null
     */
    private function gettingRegistrationToken()
    {
        $registrationToken = null;
        $html = null;

        if ($registrationToken) {
            Logger::append(sprintf("Получен 'RegistrationToken' => %s...", substr($registrationToken, 0, 100)));
        } else {
            throw new \LogicException(sprintf("Не удалось получить 'X-SkypeToken' => %s...", $html));
        }

        return $registrationToken;
    }

    /**
     * @param $refreshToken
     * @return null
     */
    private function gettingXSkypeToken($refreshToken)
    {
        // eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImtpZCI6IjIifQ.eyJpYXQiOjE0NTU5Mjg1NjMsImV4cCI6MTQ1NjAxNDk2Mywic2t5cGVpZCI6ImppbGV4YW5kci10aGUuYmVzdCIsInNjcCI6NDQ2LCJjc2kiOiIwIiwiYWF0IjoxNDU1OTE5NjM5fQ.uJ2JsHsCh64800uSllA8QRxjU0OeySs1vlcfGAFzd1-CSoigUPlj9ljJU99A6c6O4X9zHox8fB4JMcNLAXGhCqcaYJdmiSam4TVa2a2TPQ_PZ6_c1vF1HcxKuogcXIWFO_92tUuiN_tK8bkNUY1t6uh-whya-bc1ZCeUCkdGn2bx5WEDPk4tNjI14SXPKKGRwBOgN-4tsBDiijWM
        $url = 'https://login.skype.com/login/silent?response_type=postmessage&client_id=578134&redirect_uri=https%3A%2F%2Fweb.skype.com%2Fde%2F&state=silentloginsdk_1434643089469&_accept=1.0&_nc=1434643089469';

        $jar = new CookieJar();

        $jar->setCookie(new SetCookie([
            'name' => 'refresh-token',
            'value' => $refreshToken,
        ]));

        $response = $this->getClient()->get($url, [
            'cookies' => $jar
        ]);

        $html = $response->getBody()->getContents();

        preg_match("/\\\\\"skypetoken\\\\\":\\\\\"(.*?)\\\\\"/", $html, $matches);

        $XSkypeToken = null;

        if (isset($matches[1])) {
            $XSkypeToken = $matches[1];
        }

        if ($XSkypeToken) {
            Logger::append(sprintf("Получен 'X-SkypeToken' => %s...", substr($XSkypeToken, 0, 100)));
        } else {
            throw new \LogicException(sprintf("Не удалось получить 'X-SkypeToken' => %s...", $html));
        }

        return $XSkypeToken;
    }

    /**
     * @param ResponseInterface $response
     * @return string
     */
    private function parseRefreshToken(ResponseInterface $response)
    {
        $cookies = is_array($response->getHeader('Set-Cookie')) ? $response->getHeader('Set-Cookie') : [];

        $cookies1 = [];

        foreach ($cookies as $cookie) {
            $pair = explode('=', $cookie);
            $cookies1[$pair[0]] = isset($pair[1]) ? $pair[1] : null;
        }

        if (empty($cookies1['refresh-token'])) {
            throw new WebSkypeException('refresh-token не найден!', $response);
        }

        return substr($cookies1['refresh-token'], 0, 395);
    }
}