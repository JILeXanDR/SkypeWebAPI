<?php

namespace WebSkype;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use WebSkype\Session\SessionStorageInterface;

class BaseSkype
{
    /** @var SessionStorageInterface */
    protected $session;

    /** @var Client */
    private $requester;

    public function __construct(SessionStorageInterface $sessionStorageInterface)
    {
        $this->session = $sessionStorageInterface;
        $this->requester = new Client(['base_uri' => 'https://api.skype.com']);
    }

    /**
     * @param $url
     * @param string $method
     * @param array $parameters
     * @return mixed|\Psr\Http\Message\ResponseInterface
     * @throws \Exception
     */
    protected function request($url, $method = 'GET', array $parameters = [])
    {
        try {

            $options = [
                'headers' => [
                    'RegistrationToken' => $this->session->getRegistrationToken(),
                    'X-Skypetoken' => $this->session->getXSkypeToken()
                ]
            ];

            $options = array_merge_recursive($options, $parameters);

            Logger::append("Подготовка запроса '{$url}'...");

            $response = $this->requester->request($method, $url, $options);

            Logger::append("Запрос отправлен успешно, получен ответ: {$response->getBody()->getContents()}");

            return $response;

        } catch (ClientException $e) {
            $this->handleException($e);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    private function handleException(ClientException $e)
    {
        Logger::append("registrationToken: {$this->session->getRegistrationToken()}");

        throw new WebSkypeException($e->getMessage(), $e->getResponse());
    }
}