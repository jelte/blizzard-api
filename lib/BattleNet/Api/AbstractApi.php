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

namespace BattleNet\Api;

use Doctrine\Common\Cache\Cache;

use InvalidArgumentException;

/**
 * Primary API class that all children source APIs extend. Provides functionality for
 * setting the API key and region, preparing query string parameters, defining HTTP headers
 * and making HTTP requests.
 *
 * @author 		Jelte Steijaert <jelte AT 4tueel DOT be>
 * @version		0.1.0
 * 
 * @abstract
 */
abstract class AbstractApi 
{
    /**
     * Identification for this library
     */
    const USER_AGENT = 'PHP Battle.net API'; 
    
	/**
	 * Official WoW API URL.
	 */
	const API_URL = 'http://%s.battle.net/api/%s/';
	
	/**
	 * Official WoW API URL for China
	 */
	const API_URL_CN = 'http://battlenet.com.%s/api/%s/';
	
	/**
	 * Official structure of the API Signature
	 */
	const API_SIGNATURE = 'BNET %s::%s';
    
	/**
	 * For which game is this API interface.
	 * The url structure supports multiple games so this has already been included.
	 * 
	 * @access protected
	 * @var string
	 */
    protected $game;

	/**
	 * List of regions available for this api
	 * 
	 * @access protected
	 * @var array
	 */
    protected $_regionWhitelist = array();
	
    /**
     * Private API key
     * 
     * @see http://blizzard.github.com/api-wow-docs/#id3524790
     * @access private
     * @var string
     */
	private $privateApiKey;
	
    /**
     * Public API key
     * 
     * @see http://blizzard.github.com/api-wow-docs/#id3524790
     * @access private
     * @var string
     */	
	private $publicApiKey;
	
	/**
	 * Region
	 * 
	 * @access protected
	 * @var string
	 */
    protected $region;
    
    /**
     * Locale 
     *
     * @access protected 
     * @var string
     */
    protected $locale;
    
    /**
     * Combination of the API_URL, $region and $game.
     * as this should not be assembled every call.
     * 
     * @access protected
     * @var string
     */
    protected $url;
    
    /**
     * The caching interface, it is highly recommended to use a persistant caching
     * 
     * @var Cache
     */
    protected $_cache;
    
    /**
     * Constructor
     * 
	 * @access public
	 * @param array $config
	 * @return void
	 * @constructor
     */
	public function __construct(array $config = array())
	{
	    $this->loadConfig($config);
	}
	
	/**
	 * Store configuration.
	 * An ApiException will be thrown when the configurations option is not recognized.
	 * 
	 * @access protected
	 * @param array $config
	 * @return void
	 * @throws ApiException
	 */
	protected function loadConfig(array $config = array())
	{
	    foreach ( $config as $key => $value ) {
	        $method = 'set'.ucfirst($key);
	        if ( method_exists($this, $method) ) {
	            $this->$method($value);
	        } else {
	            throw new ApiException('Unknown config parameter "'.$key.'"');
	        }
	    }
	}
	
	/**
	 * Set the cache interface
	 * 
	 * @access public
	 * @param Cache $cache
	 * @return void
	 */
	public function setCache(Cache $cache)
	{
	    $this->_cache = $cache;
	}
	
	/**
	 * Set the region and reset the region url.
	 * 
	 * @access public
	 * @param string $region
	 * @return void
	 */
	public function setRegion($region)
	{
	    $this->_checkAvailabilityRegionAndLocale($region, $this->locale);
	    $this->region = $region;
	    $this->_setApiUrl();        
	}
	
	/**
	 * When the region is China a different url structure is used
	 * 
	 * @access private
	 * @return void
	 */
	private function _setApiUrl()
	{
	    $url = self::API_URL;
	    if ( $this->region == 'cn' ) {
            $url = self::API_URL_CN;
	    }	
	    $this->url = sprintf($url, $this->region, $this->game);
	}

	/**
	 * Set the locale, the language the result should be in. 
	 * 
	 * @access public
	 * @param string $locale
	 * @return void
	 */
	public function setLocale($locale)
	{
        $this->_checkAvailabilityRegionAndLocale($this->region, $locale);
	    $this->locale = $locale;
	}
	
	/**
	 * Ensure a valid region and locale is selected
	 * 
	 * @access private
	 * @param string $region
	 * @param string $locale
	 * @throws ApiException
	 * @return boolean
	 */
	private function _checkAvailabilityRegionAndLocale($region, $locale)
	{
	    if ( $region ) {
    	    if ( !array_key_exists($region, $this->_regionWhitelist) ) {
    	        throw new InvalidArgumentException(sprintf('Region "%s" is not available.', $region));
    	    }
    	    if ( $locale ) {
        	    if ( !in_array($locale, $this->_regionWhitelist[$region]) ) {
        	        throw new InvalidArgumentException(sprintf('Locale "%s" is not available for region "%s".', $locale, $region));
        	    }
    	    }
	    }
	    return true;
	}
	
	/**
	 * Set the private Api Key
	 * 
	 * @access public
	 * @param string $privateApiKey
	 * @return void
	 */
	public function setPrivateApiKey($privateApiKey)
	{
	    $this->privateApiKey = $privateApiKey;
	}
	
	/**
	 * Set the public Api Key
	 * 
	 * @access public
	 * @param string $publicApiKey
	 * @return void
	 */
	public function setPublicApiKey($publicApiKey)
	{
	    $this->publicApiKey = $publicApiKey;
	}
	
	/**
	 * Get the base url of the API 
	 * 
	 * @access public
	 * @return string
	 */
	public function getUrl()
	{
	    return $this->url;
	}
	
	/**
	 * Execute the Api Call
	 * 
	 * @access public
	 * @param AbstractCall $call
	 * @return ApiResponse
	 * @throws ApiException
	 */
    public function request(AbstractCall $call)
    {
        // asssemble the url that will be called
        $url = $this->getUrl().$call->getPath();
        
        // assemble the query string
        $queryParams = $this->_getQueryParams($call);
                
        if ( $call->getMethod() == 'GET' ) {
            $response = $this->_getRequest($call, $url, $queryParams);    
        } else {
            $response = $this->_postRequest($call, $url, $queryParams);   
        }
                
        return $response;        
    }
    
    /**
     * assemble the Query string.
     * 
     * @access protected
     * @param AbstractCall $call
     * @return string
     */
    protected function _getQueryParams(AbstractCall $call)
    {
        $queryParams = $call->getQueryParams();
        
        // Check if the locale is set
        if ( $this->locale ) {
            $queryParams['locale'] = $this->locale;
        }
        return http_build_query($queryParams); 
    }
    
    /**
     * Execute the GET Api Call
     * 
     * @access private
     * @param AbstractCall $call
     * @param string $baseUrl
     * @param string $queryParams
     * @return ApiResponse
     */
    private function _getRequest(AbstractCall $call, $baseUrl, $queryParams)
    {
        // append the uri with the queryParams
        $url = $baseUrl.'?'.$queryParams;
        
        // retrieve the cacheId for this url.
        $cacheId = $this->_getCacheId($url);
        
        // check if this request isn't already cached
        if ( $this->_isCached($cacheId) ) {
            // retrieved the cached response
            $response = $this->_fromCache($cacheId);
        } else {
            // build the context for the http call to the blizzard api
            $context = $this->_buildContext($call->getMethod(), $baseUrl);
            
            // retrieve the data from the blizzard api
            $rawResponse = @file_get_contents($url,false,$context);
            
            // build the response
            $response = new ApiResponse($url, $http_response_header, $rawResponse);
            
            // cache the response
            $this->_cache($cacheId,$response);
        }
        
        return $response;
    }

    /**
     * if in the future it might be possible to send information back to the Api (calendar accepts orso)
     * this will probably be done through POST method.
     * and the handling is slightly different.
     * 
     * @access private
     * @param AbstractCall $call
     * @param unknown_type $url
     * @param unknown_type $queryParams
     * @throws ApiException
     */
    private function _postRequest(AbstractCall $call, $url, $queryParams)
    {
        throw new ApiException('POST method not yet used/supported by Blizzard');
    }
    
    /**
     * Build the header for the http request to the Blizzard Api
     * 
     * @access private
     * @param string $method
     * @param string $url
     * @return resource
     */
    private function _buildContext($method, $url)
    {
        // Create the basic options
        $opts = array(
          'http'=>array(
            'method'=>$method,
            'header'=>'User-Agent: '.self::USER_AGENT."\r\n"  
          )
        );

        // if the private Api Key is set append the header with the needed signature
        if ( $this->privateApiKey ) {
            $date = date(DATE_RFC2822);
            $opts['http']['header'] .= "Date: ".$date;
            $opts['http']['header'] .= "Authorization: ".$this->_signRequest($method, $date, $url)."\r\n";
        }  
        
        return stream_context_create($opts);
    }
    
    /**
     * Build the Authorization signature
     * 
     * @access private
     * @param string $method
     * @param string $date
     * @param string $uri
     * @return string
     */
    private function _signRequest($method, $date, $uri)
    {
        $signature = base64_encode(hash_hmac('sha1',$this->privateApiKey,implode("\n",func_get_args())."\n"));
        return sprintf(self::API_SIGNATURE,$this->publicApiKey,$signature);
    }
    
    /**
     * Check if a request already exists in the cache
     * 
     * @access private
     * @param string $cacheId
     * @return boolean
     */
    private function _isCached($cacheId)
    {
        // check if a cache interface is set
        if ( isset($this->_cache) ) {
            return $this->_cache->contains($cacheId);
        }
        return false;
    }
    
    /**
     * Fetch a request from cache
     * 
     * @access private
     * @param string $cacheId
     * @return ApiResponse
     */
    private function _fromCache($cacheId)
    {
        return $this->_cache->fetch($cacheId);
    }
    
    /**
     * Save a response in the cache
     *
     * @param string $cacheId
     * @param ApiResponse $response
     * @return boolean TRUE if the entry was successfully stored in the cache, FALSE otherwise.
     */
    private function _cache($cacheId, $response)
    {
        // check if a cache interface is set
        if ( isset($this->_cache) ) {
            return $this->_cache->save($cacheId, $response, $response->getLifeTime());
        }
        return false;
    }
    
    private function _getCacheId($url)
    {
        return md5($url);
    }
}