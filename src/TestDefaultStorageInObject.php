<?php

namespace WebSkype;

use WebSkype\Session\SessionStorageInterface;

/**
 * Хранение данных токенов в свойствах
 * Class ClassStorage
 * @package WebSkype
 */
class TestDefaultStorageInObject implements SessionStorageInterface
{
    private $RefreshToken;
    private $RegistrationToken = 'registrationToken=U2lnbmF0dXJlOjI6Mjg6QVFRQUFBQXpvVzF4cVZZNWpyL2xUZldMUGdobztWZXJzaW9uOjY6MToxO0lzc3VlVGltZTo0OjE5OjUyNDc2MDExODI4ODY0NzQ3MTc7RXAuSWRUeXBlOjc6MTo4O0VwLklkOjI6MTg6amlsZXhhbmRyLXRoZS5iZXN0O0VwLkVwaWQ6NTozNjpkOWRmODE1ZC0yNWIyLTRmOGQtYmNiOS1mY2YyZTdlMmE1YTI7RXAuTG9naW5UaW1lOjc6MTowO0VwLkF1dGhUaW1lOjQ6MTk6NTI0NzYwMTE4Mjg4MjA5OTcxNztFcC5BdXRoVHlwZTo3OjI6MTU7VXNyLk5ldE1hc2s6MTE6MToyO1Vzci5YZnJDbnQ6NjoxOjA7VXNyLlJkcmN0RmxnOjI6MDo7VXNyLkV4cElkOjk6MTowO1Vzci5FeHBJZExhc3RMb2c6NDoxOjA7VXNlci5BdGhDdHh0OjI6MjcyOkNsTnJlWEJsVkc5clpXNFNhbWxzWlhoaGJtUnlMWFJvWlM1aVpYTjBBUU5WYVdNVU1TOHhMekF3TURFZ01USTZNREE2TURBZ1FVME1UbTkwVTNCbFkybG1hV1ZrQUFBQUFBQUFBQUFBQUFBQUFBQkFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFFbXBwYkdWNFlXNWtjaTEwYUdVdVltVnpkQUFBQUFBQUFBQUFBQWRPYjFOamIzSmxBQUFBQUFRQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQkVtcHBiR1Y0WVc1a2NpMTBhR1V1WW1WemRBQUFBQUE9Ow==';
    private $XSkypeToken = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImtpZCI6IjIifQ.eyJpYXQiOjE0NTU5MTk2MzksImV4cCI6MTQ1NjAwNjAzOSwic2t5cGVpZCI6ImppbGV4YW5kci10aGUuYmVzdCIsInNjcCI6NDQ2LCJjc2kiOiIwIiwiYWF0IjoxNDU1OTE5NjM5fQ.epRaED5eUwf4LfoqXl1Qc5WHoW6SQToVxuN6nwxbv5Wg3YJfO52PMEfSZP0nGH6-RUrkKZA4A0RT7su2tAICrHJTkMBSTn9nQT33ktqFGk7_m5SnYZnz7KEioQENp5qw_K3TxVArn7Rq3Ry84csrv0hoWT3_04SLvuhkpjXlDX47yTgwssHpNe5DSn5Wz7JYTL3pGanH7_EbjQE5';

    /**
     * ClassStorage constructor.
     * @param bool $clear Имитация отсутствия токенов
     */
    public function __construct($clear = false)
    {
        if ($clear) {
            $this->setRefreshToken(null);
            $this->setRegistrationToken(null);
            $this->setXSkypeToken(null);
        }
    }

    /**
     * @return mixed
     */
    public function getRegistrationToken()
    {
        return $this->RegistrationToken;
    }

    /**
     * @return mixed
     */
    public function getRefreshToken()
    {
        return $this->RefreshToken;
    }

    /**
     * @return mixed
     */
    public function getXSkypeToken()
    {
        return $this->XSkypeToken;
    }

    public function setRegistrationToken($token)
    {
        $this->RegistrationToken = $token;

        return $this;
    }

    public function setRefreshToken($token)
    {
        $this->RefreshToken = $token;

        return $this;
    }

    public function setXSkypeToken($token)
    {
        $this->XSkypeToken = $token;

        return $this;
    }
}