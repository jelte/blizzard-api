<?php
namespace BattleNet\Http\Response;

use BattleNet\Api\ApiResponse;
use DateTime;
use DateTimeZone;

abstract class AbstractResponse
implements ApiResponse
{
    /**
     * The url of the HTTP request
     *
     * @access private
     * @var string
     */
    protected $url;

    protected $responseCode;

    protected $responseMessage;

    /**
     * The HTTP response
     *
     * @access private
     * @var string
     */
    protected $response;

    /**
     * Headers of the HTTP response
     *
     * @access private
     * @var array
     */
    protected $headers;

    /**
     * Date of the HTTP request
     *
     * @access private
     * @var string
     */
    protected $date;

    /**
     * Expire date of the HTTP response
     *
     * @access private
     * @var string
     */
    protected $expires;

    public function __construct($url, $headers, $response)
    {
        $this->url = $url;
        $this->headers = $headers;
        $this->response = $response;
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function getResponseCode()
    {
        return $this->responseCode;
    }
    
    public function getResponseMessage()
    {
        return $this->responseMessage;
    }
    
    /**
     * Get the date of the request
     *
     * @access public
     * @return string
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Get the date when the response expires
     *
     * @access public
     * @return string
     */
    public function getExpires()
    {
        return $this->expires;
    }

    /**
     * Get the Life Time of this response
     *
     * @todo balance timezones
     *
     * @access public
     * @return integer
     */
    public function getTTL()
    {
        $date = new DateTime();
        $date->setTimezone(new DateTimeZone('GMT'));
        $ttl = strtotime($this->getExpires())-$date->getTimestamp();
        return $ttl;
    }

    /**
     * Get the url of the request
     *
     * @access public
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Get the raw json response
     *
     * @access public
     * @return string
     */
    public function getJson()
    {
        return $this->response;
    }

    /**
     * Get the decoded json response
     *
     * @param boolean $asArray if True the response will be an array, if FALSE the response will be a stdClass
     * @return mixed
     */
    public function getData($asArray = false)
    {
        return json_decode($this->response, $asArray);
    }
}