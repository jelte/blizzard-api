<?php
/**
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace Khepri\BattleNet\Api;

/**
 * The basic API response class.
 * 
 * @todo convert the response data to specific classes depending on the call
 * 		 - a ItemCall should return an Item,
 * 		 - a CharacterCall should return a Character
 *
 * @author 		Jelte Steijaert <jelte AT 4tueel DOT be>
 * @version		0.1.0
 * 
 * @abstract
 */
class ApiResponse
{
    /**
     * The url of the HTTP request
     * 
     * @access private
     * @var string
     */
    private $url;

    /**
     * The HTTP response
     * 
	 * @access private
     * @var string
     */
    private $response;
    
    /**
     * Headers of the HTTP response
     * 
     * @access private
     * @var array
     */
    private $headers;
    
    /**
     * Date of the HTTP request
     * 
     * @access private
     * @var string
     */
    private $date;

    /**
     * Expire date of the HTTP response
     * 
     * @access private
     * @var string
     */
    private $expires;
    
    /**
     * Constructor
     * 
     * @access public
     * @param string $url
     * @param array $headers
     * @param string $response
     * @constructor
     * @return void
     */
    public function __construct($url, array $headers, $response)
    {
        $this->url = $url;
        $this->response = $response;
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
        $this->headers = $headers;

        // check each header line
        foreach ( $headers as $header ) {
            $parts = explode(':',$header);
            $property = trim(strtolower(array_shift($parts)));
            $value = trim(implode(':',$parts));
            
            // check if this property is defined
            if ( property_exists($this, $property) ) {
                $this->$property = $value;
            }
        }
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
    public function getLifeTime()
    {
        return strtotime($this->expires)-time();   
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