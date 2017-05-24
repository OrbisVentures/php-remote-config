<?php

namespace SpringConfig\RemoteConfig;


use SpringConfig\RequestClient\RequestClient;

class RemoteConfig
{
    static $client = null;

    /**
     * @param array $services
     */
    static function get(array $services)
    {
        if(is_null(self::$client)) {
            self::$client = new RequestClient();
        }
        return self::$client->getConfig(['']);
    }
}