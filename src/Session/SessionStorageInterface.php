<?php

namespace WebSkype\Session;

interface SessionStorageInterface extends SessionTokenInterface
{
    /**
     * @param $token
     * @return $this
     */
    public function setRegistrationToken($token);

    /**
     * // TODO скорее всего этот токен не нужен
     * @param $token
     * @return $this
     */
    public function setRefreshToken($token);

    /**
     * @param $token
     * @return $this
     */
    public function setXSkypeToken($token);
}