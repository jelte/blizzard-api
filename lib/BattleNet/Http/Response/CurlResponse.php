<?php
namespace BattleNet\Http\Response;

class CurlResponse
extends AbstractResponse
{
     
    public function __construct($url, array $headers, $response, $responseCode, $responseMessage)
    {
        $this->url = $url;
        $this->headers = $headers;
        $this->response = $response;
        $this->responseCode = $responseCode;
        $this->responseMessage = $responseMessage;
    }

}