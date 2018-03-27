<?php

namespace MtOpenApi\Api;

use Exception;
use MtOpenApi\Config\Config;
use MtOpenApi\Protocol\Client;

class RequestService
{
    const METHOD_POST = 'post';
    const METHOD_GET = 'get';

    /** @var Client  */
    protected $client;
    protected $action='';
    protected $params=array();
    protected $method='';
    protected $rows_num = "50";

    public function __construct($token,Config $config)
    {
        $this->client = new Client($token, $config);
    }

    public function check()
    {
        return true;
    }

    public function action()
    {
        return $this->action;
    }

    public function params()
    {
        return $this->params;
    }

    public function method()
    {
        return $this->method;
    }

    /**
     * @param string $action
     * @param array $params
     * @param string $method
     * @return mixed
     */
    public function call($action='',$params=[],$method='get')
    {
        $this->action = $action;
        $this->params = $params;
        $this->method = $method;
        try {
            return $this->client->execute($this);
        } catch (Exception $e) {
            throw $e;
        }
    }

}