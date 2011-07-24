<?php
namespace BattleNet\Http\Response;

class CurlResponse
extends AbstractResponse
{
    /**
     * Constructor 
     * 
     * @access public
     * @param unknown_type $url
     * @param array $headers
     * @param unknown_type $response
     * @param unknown_type $responseCode
     * @param unknown_type $responseMessage
     * @constructor
     * @return void
     */
    public function __construct($url, array $headers, $response, $responseCode, $responseMessage)
    {
        parent::__construct($url, $headers, $response);
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