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
        $this->_parseHeaders($headers);
    }

    /**
     * Parse the lines from the response header and set properties accordingly
     *
     * @access private
     * @param array $headers
     * @return void
     */
    private function _parseHeaders(array $headers)
    {
        $this->responseCode = $headers['http_code'];
        if ( $this->responseCode !== 200 ) {
            $this->responseMessage = $this->getData()->reason;
        }
    }
}