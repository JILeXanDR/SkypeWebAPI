<?php

namespace WebSkype;

use Psr\Http\Message\ResponseInterface;

class Skype extends BaseSkype
{
    /**
     *
     */
    const API_SEND_MESSAGE = 'https://client-s.gateway.messenger.live.com/v1/users/ME/conversations/8:%s/messages';

    /**
     * $skypeGroup = 8667611380234649bf560c87fc5c19bc@thread.skype
     */
    const API_SEND_GROUP_MESSAGE = 'https://client-s.gateway.messenger.live.com/v1/users/ME/conversations/19:%s/messages';

    /**
     *
     */
    const API_SELF_PROFILE = 'https://api.skype.com/users/self/profile';

    /**
     *
     */
    const API_PROFILES = 'https://api.skype.com/users/batch/profiles';

    /**
     * @return string
     * @throws \Exception
     */
    public function profile()
    {
        $response = $this->request(static::API_SELF_PROFILE, 'GET');

        return $response->getBody()->getContents();
    }

    /**
     * @param $content
     * @param $skypeUser
     * @return ResponseInterface
     */
    public function sendMessage($content, $skypeUser)
    {
        $message = new Message($content);

        Logger::append("Отправить сообщение '{$message}' пользователю '{$skypeUser}'...");

        $url = sprintf(static::API_SEND_MESSAGE, $skypeUser);

        return $this->request($url, 'POST', [
            'body' => $message
        ]);
    }

    /**
     * @param $content
     * @param $skypeGroup
     * @return mixed|ResponseInterface
     */
    public function sendGroupMessage($content, $skypeGroup)
    {
        $message = new Message($content);

        Logger::append("Отправить сообщение '{$message->content}' группе '{$skypeGroup}'...");

        $url = sprintf(static::API_SEND_GROUP_MESSAGE, $skypeGroup);

        return $this->request($url, 'POST', [
            'body' => $message
        ]);
    }

    public function profiles()
    {
        return $this->request(static::API_PROFILES, 'POST', [
            'body' => json_encode([
                'usernames' => ['jilexandr']
            ])
        ]);
    }
}