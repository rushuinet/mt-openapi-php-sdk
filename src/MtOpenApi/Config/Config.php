<?php

namespace MtOpenApi\Config;

use Exception;

class Config
{
    private $app_key;
    private $app_secret;
    private $sandbox;

    private $request_url;

    private $log;

    private $default_request_url = "http://waimaiopen.meituan.com/api/v1/";
    private $default_sandbox_request_url = "http://waimaiopen.meituan.com/api/v1/";

    public function __construct($app_key, $app_secret, $sandbox)
    {
        if ($sandbox == false) {
            $this->request_url = $this->default_request_url;
        } elseif ($sandbox == true) {
            $this->request_url = $this->default_sandbox_request_url;
        } else {
            throw new Exception("the type of sandbox should be a boolean");
        }

        if ($app_key == null || $app_key == "") {
            throw new Exception("app_key is required");
        }

        if ($app_secret == null || $app_secret == "") {
            throw new Exception("app_secret is required");
        }

        $this->app_key = $app_key;
        $this->app_secret = $app_secret;
        $this->sandbox = $sandbox;
    }

    public function get_app_key()
    {
        return $this->app_key;
    }

    public function get_app_secret()
    {
        return $this->app_secret;
    }

    public function get_request_url()
    {
        return $this->request_url;
    }

    public function set_request_url($request_url)
    {
        $this->request_url = $request_url;
    }

    public function get_log()
    {
        return $this->log;
    }

    public function set_log($log)
    {
        if (!method_exists($log, "info")) {
            throw new Exception("logger need have method 'info(\$message)'");
        }
        if (!method_exists($log, "error")) {
            throw new Exception("logger need have method 'error(\$message)'");
        }
        $this->log = $log;
    }
}