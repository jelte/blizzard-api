<?php
namespace BattleNet\Http\Adapter;

use BattleNet\Http\Response\CurlResponse;

use BattleNet\Http\HttpException;

class CurlAdapter
extends AbstractAdapter
{
    protected $_options = array(
    CURLOPT_TIMEOUT        => 10,
    CURLOPT_ENCODING       => "gzip",
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_SSL_VERIFYHOST => false,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_VERBOSE        => false
    );

    /**
     * Adapter constructor
     *
     * @access public
     * @return void
     * @throws HttpException
     */
    public function __construct()
    {
        // Check if curl extension is loaded
        if (!extension_loaded('curl')) {
            throw new HttpException('cURL extension has to be loaded to use this Http adapter.');
        }
    }

    protected function _doRequest($method, $url, $queryString)
    {
        $this->_options[CURLOPT_URL] = $url.'?'.$queryString;
        $this->_options[CURLOPT_HTTPHEADER] = $this->headers;

        $curl = curl_init();
        curl_setopt_array($curl, $this->_options);

        $response = curl_exec($curl);
        $headers = curl_getinfo($curl);
        $responseCode = curl_errno($curl);
        $responseMessage = curl_error($curl);

        curl_close($curl);
                
        return new CurlResponse($url, $headers, $response, $responseCode, $responseMessage);
    }
}