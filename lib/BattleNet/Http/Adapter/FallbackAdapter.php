<?php
namespace  BattleNet\Http\Adapter;

use BattleNet\Http\Response\FallbackResponse;

use BattleNet\Api\ApiResponse;

class FallbackAdapter
extends AbstractAdapter
{
    protected function _doRequest($method, $url, $queryString)
    {
        // build the context for the http call to the blizzard api
        $context = $this->_buildContext($method);

        // uri
        $uri = $url.'?'.$queryString;

        // retrieve the data from the blizzard api
        $rawResponse = @file_get_contents($uri,false,$context);

        // build the response
        return new FallbackResponse($uri, $http_response_header, $rawResponse);
    }

    /**
     * Build the header for the http request to the Blizzard Api
     *
     * @access private
     * @param string $method
     * @param string $url
     * @return resource
     */
    private function _buildContext($method)
    {
        // Create the basic options
        $opts = array(
          'http'=>array(
            'method'=>$method,
            'header'=>$this->getHeaders()  
        )
        );
        return stream_context_create($opts);
    }
}