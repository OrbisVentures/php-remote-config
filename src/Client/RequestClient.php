<?php
namespace RemoteConfig\Client;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Promise;

class RequestClient
{
    /**
     * @var GuzzleClient
     */
    private $guzzleClient = null;

    /**
     * @var array
     */
    private $config;

    public function __construct(array $config = null)
    {
        $timeout = getenv("CONFIG_TIMEOUT");
        $profile = getenv("CONFIG_PROFILE");
        $this->config = [
            "host"      => getenv("CONFIG_HOST"),
            "user"      => getenv("CONFIG_USER"),
            "password"  => getenv("CONFIG_PASSWORD"),
            "timeout"   => $timeout?$timeout:5,
            "env"       => getenv("CONFIG_ENV"),
            "prefix"    => getenv("CONFIG_URI_PREFIX"),
            "profile"   => $profile?$profile:"default"
        ];

        if (!is_null($config)) {
            $this->config = array_merge($this->config, $config);
        }
    }

    public function getConfig(array $services)
    {
        $client = $this->getClient();
        $requestPromises = [];
        foreach ($services as $service) {
            $uri = "/{$service}-{$this->config['profile']}.json";
            if ($this->config['env']) {
                $uri = "/{$this->config['env']}{$uri}";
            }
            if($this->config['prefix']) {
                $uri = "{$this->config['prefix']}{$uri}";
            }
            $requestPromises[$service] = $client->getAsync($uri);
        }
        $results = Promise\settle($requestPromises)->wait();
        $arrayResult = [];
        foreach ($results as $key => $result) {
            if(isset($result['value']) && $result['state'] == 'fulfilled') {
                $arrayResult[$key] = $this->filterConfig(json_decode($result['value']->getBody()->getContents(),true));
            } elseif ($result['state'] == 'rejected') {
                /*$arrayResult[$key] = [
                    'error' => 'true',
                    'messaje' => $result['reason']->getMessage()
                ];*/
            } else {
                //$arrayResult[$key] = [];
            }
        }
        return $arrayResult;
    }

    private function getClient()
    {
        if(is_null($this->guzzleClient)) {
            $parameters = [
                'base_uri' => $this->config['host'],
                'timeout'  => $this->config['host']
            ];
            if($this->config["user"] && $this->config["password"]) {
                $parameters['headers'] = ['Authorization' => 'Basic '. base64_encode($this->config["user"].':'.$this->config["password"])];
            }
            $this->guzzleClient = new GuzzleClient($parameters);
        }
        return $this->guzzleClient;
    }

    /**
     * @param array $configResult
     * @return array
     */
    private function filterConfig(array $configResult)
    {
        foreach ($configResult as $keyConfig => $value) {
            if ($value == 'true' ) {
                $configResult[$keyConfig] = true;
            } elseif($value == 'false') {
                $configResult[$keyConfig] = false;
            }
        }
        return $configResult;
    }
}