<?php
namespace SpringConfig\RequestClient;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Promise;

class RequestClient
{
    /**
     * @var GuzzleClient
     */
    private $guzzleClient = null;

    private $config;

    public function __construct(array $config = null)
    {
        $timeout = getenv("CONFIG_TIMEOUT");
        $env = getenv("CONFIG_ENV");
        $this->config = [
            "host"      => getenv("CONFIG_HOST"),
            "user"      => getenv("CONFIG_USER"),
            "password"  => getenv("CONFIG_PASSWORD"),
            "timeout"   => is_null($timeout)?$timeout:5,
            "env"       => is_null($env)?$env:'default'
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
            $uri = "/{$service}-{$this->config['env']}.json";
            if(!is_null($this->config["user"]) && !is_null($this->config["password"])) {
                $requestPromises[$service] = $client->request('GET', $uri, ['auth' => [$this->config["user"], $this->config["password"]]]);
            }
            $requestPromises[$service] = $client->request('GET', $uri);
        }
        $results = Promise\settle($requestPromises)->wait();
        var_dump($results);exit;
    }

    private function getClient()
    {
        if(is_null($this->guzzleClient)) {
            $this->guzzleClient = new GuzzleClient([
                'base_uri' => $this->config['host'],
                'timeout'  => $this->config['host'],
            ]);
        }
        return $this->guzzleClient;
    }
}