<?php
namespace SpringConfig\Client;

class QueryParameters
{
    /**
     * @var string
     */
    private $env = "";

    /**
     * @var string
     */
    private $service = null;

    /**
     * @var string
     */
    private $parameter = null;

    /**
     * @var string
     */
    private $user = null;

    /**
     * @var string
     */
    private $password = null;

    /**
     * @param string $env
     * @return QueryParameters $this
     */
    public function setEnv($env)
    {
        $this->env = $env;
        return $this;
    }

    /**
     * @param $service
     * @return QueryParameters $this
     */
    public function setService($service)
    {
        $this->service = $service;
        return $this;
    }

    /**
     * @param string $parameter
     * @return QueryParameters $this
     */
    public function setParameter($parameter)
    {
        $this->parameter = $parameter;
        return $this;
    }

    /**
     * @param string $user
     * @return QueryParameters $this
     */
    public function setUser(string $user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @param string $password
     * @return QueryParameters $this
     */
    public function setPassword(string $password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return string
     */
    public function getEnv(): string
    {
        return $this->env;
    }

    /**
     * @return string
     */
    public function getService(): string
    {
        return $this->service;
    }

    /**
     * @return string
     */
    public function getParameter(): string
    {
        return $this->parameter;
    }

    /**
     * @return string
     */
    public function getUser(): string
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }
}