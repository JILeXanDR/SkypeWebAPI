<?php

namespace WebSkype\Session;

interface SessionTokenInterface
{
    /**
     * @return mixed
     */
    public function getRegistrationToken();

    /**
     * // TODO скорее всего этот токен не нужен
     * @return mixed
     */
    public function getRefreshToken();

    /**
     * @return mixed
     */
    public function getXSkypeToken();
}