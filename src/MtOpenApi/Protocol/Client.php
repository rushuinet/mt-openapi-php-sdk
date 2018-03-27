<?php
namespace MtOpenApi\Protocol;
use Exception;
use MtOpenApi\Api\RequestService;
use MtOpenApi\Config\Config;
use stdClass;

/**
 * Class Client
 */
class Client
{
	public $app_id;
	public $app_key;
    public $app_secret;
    public $token;
    public $api_request_url;

	protected $connectTimeout 	= 3000;
	protected $readTimeout 		= 60000;


    public function __construct($token, Config $config)
    {
        $this->app_key = $config->get_app_key();
        $this->app_secret = $config->get_app_secret();
        $this->api_request_url = $config->get_request_url();
        $this->log = $config->get_log();
        $this->token = $token;
    }

    protected function generateSign($action,$params,$method)
	{
        $stringToBeSigned = $this->paramsToString($action,$params,$method);
		return md5($stringToBeSigned);
	}

	protected function verifySign($action,$params,$method)
	{
        $stringToBeSigned = $this->paramsToString($action,$params,$method);

        $sign = $params["sign"];
        if(!empty($sign)){
        	return $sign == md5($stringToBeSigned);
        }
        return false;
	}

    protected function paramsToString($action,$params,$method)
    {
		ksort($params);
		$sortedString = $this->api_request_url.$action.'?';
		$urls = [];
		foreach ($params as $k => $v)
		{
            $v = (string)$v;
			if("sign" !== $k || 'img_data' != $k)
			{
                $urls[$k] = $v;
			}
		}
        $sortedString .= urldecode(http_build_query($urls));
		$sortedString .= $this->app_secret;
        return $sortedString;
    }
	protected function query_str_fetch(array $fields, $encoder="")
	{
	    $qs = array();
	    foreach($fields as $key=>$val){
	        $qs[] = "{$key}=". (function_exists($encoder)? $encoder($val) : $val);
	    }
	    return implode("&",$qs);
	}

    /**
     * @param $url
     * @param null $postFields
     * @return mixed
     * @throws Exception
     */
    public function curl($url, $postFields = null,$method='get')
	{
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "X-HTTP-Method-Override: $method"
        ));
        if($method == RequestService::METHOD_POST) {
            $url .=  '?app_id=' .$postFields['app_id'].'&timestamp=' . $postFields['timestamp'].'&sig='.$postFields['sig'];
            unset($postFields['app_id'],$postFields['timestamp'],$postFields['sig']);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postFields));
        } else {
            curl_setopt($ch, CURLOPT_URL, $url . '?' . http_build_query($postFields));
        }
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3); // 发起连接前等待的时间
        curl_setopt($ch, CURLOPT_TIMEOUT, 5); // 执行时间
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 不验证证书
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); // 不验证证书

        $reponse = curl_exec($ch);

		if (curl_errno($ch))
		{
			throw new Exception(curl_error($ch), 0);
		}
		else
		{
			$httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			if (200 !== $httpStatusCode)
			{
				throw new Exception($reponse, $httpStatusCode);
			}
		}
		curl_close($ch);
		return $reponse;
	}

    /**
     * @param RequestService $request
     * @return mixed
     * @throws Exception
     */
    public function execute(RequestService $request)
    {
        try 
        {
			$request->check();
        } 
        catch (Exception $e) 
        {
            throw $e;
		}

		$action = $request->action();
		$apiParams = array();
		//系统级别参数
		$apiParams['app_id']     = $this ->app_key;
        $apiParams['timestamp']   = time();
        $apiParams = array_merge($apiParams,$request->params());
		$apiParams["sig"] 		  = $this->generateSign($action,$apiParams,$request->method());
		try
		{
			$requestUrl = $this->api_request_url . $action;
			//var_dump($requestUrl,$apiParams);die;
			$resp = $this->curl($requestUrl,$apiParams,$request->method());
            $log = $this->log;
            if ($log != null) {
                $log->info("request data: " . json_encode($apiParams, JSON_UNESCAPED_UNICODE));
                $log->info("response data: " . $resp);
            }
			echo "<br>url=>" . $requestUrl;
			echo "<br>params=>" . json_encode($apiParams, JSON_UNESCAPED_UNICODE);
            echo "<br>response=>" . $resp;
		}
		catch (Exception $e)
		{
			throw $e;
	    }
        $resp = json_decode($resp,true);
		if(isset($resp['error']['code']))
        {
            throw new Exception($resp['error']['code'],$resp['error']['msg']);
        }
        return $resp['data'];
	}

}
