<?php

namespace WebSkype\Session;

use WebSkype\Logger;
use WebSkype\LoginConnection;

class SessionToken
{
    /** @var SessionStorageInterface */
    public $storage;

    /**
     * Session constructor.
     * @param SessionStorageInterface $storage
     */
    private function __construct(SessionStorageInterface $storage)
    {
        $this->storage = $storage;
    }

    /**
     * @param SessionStorageInterface $sessionStorageInterface
     * @return SessionToken
     */
    public static function setStorage(SessionStorageInterface $sessionStorageInterface)
    {
        return new SessionToken($sessionStorageInterface);
    }

    /**
     * Поиск существующей сессии или создание новой
     * @param $username
     * @param $password
     * @return static
     */
    public function findActiveOrCreateNew($username, $password)
    {
        Logger::append("Поиск существующей сессии или создание новой");

        $registrationToken = $this->storage->getRegistrationToken();
        $xSkypeToken = $this->storage->getXSkypeToken();

        if ($registrationToken && $xSkypeToken) {
            Logger::append("В хранилище обнаружены данные сессии");
        } else {
            Logger::append("В хранилище не обнаружено данных сессии. Авторизоваться с помощью логина и пароля...");
            $loginConnection = new LoginConnection($this->storage);
            $this->storage = $loginConnection->login($username, $password);
        }

        return $this;
    }
}