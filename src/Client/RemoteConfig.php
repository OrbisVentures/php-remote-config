<?php

namespace RemoteConfig\Client;

class RemoteConfig
{
    static $client = null;

    /**
     * @param string|array $services
     * @return array
     */
    static function get($services)
    {
        if(is_null(self::$client)) {
            self::$client = new RequestClient();
        }
        if(is_array($services)) {
            return self::$client->getConfig($services);
        }
        return self::$client->getConfig([$services])[$services];
    }
}