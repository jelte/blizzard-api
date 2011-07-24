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

use BattleNet\Http\HttpException;

use BattleNet\Http\Adapter\AbstractAdapter;

use BattleNet\Http\Adapter\CurlAdapter;

use BattleNet\Http\Adapter\FallbackAdapter;

use BattleNet\Cache\Cache;

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
	const API_URL = 'http://%s.battle.net/api/%s/%s';
	
	/**
	 * Official WoW API URL for China
	 */
	const API_URL_CN = 'http://battlenet.com.%s/api/%s/%s';
	
	/**
	 * Official structure of the API Signature
	 */
	const API_SIGNATURE = 'BNET %s:%s';
    
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
     * @var AbstractAdapeter
     */
    protected $_httpAdapter;
    
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
	            throw new InvalidArgumentException('Unknown config parameter "'.$key.'"');
	        }
	    }
	}
	
	public function setHttpAdapter($adapter)
	{
	    if ( is_string($adapter) ) {
	        $adapterName = '\BattleNet\\Http\\Adapter\\'.ucfirst($adapter).'Adapter';
	        $adapter = new $adapterName();
	    } 
	    if ( !($adapter instanceof AbstractAdapter) ) {
	        throw new HttpException('Unknown HTTP Adapter');
	    }
	    $this->_httpAdapter = $adapter;
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
	    $this->_httpAdapter->setCache($cache);
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
	    $this->url = $url;
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
	
	public function request(AbstractCall $call)
	{
	    $method = $call->getMethod();
	    $date = date(DATE_RFC2822);
	    $url = sprintf($this->url, $this->region, $this->game, $call->getPath());
	    
	    $options = array();
	    $options['headers']['User-Agent'] = self::USER_AGENT;
	    $options['headers']['Expect'] = '';
	    $options['headers']['Accept'] = 'application/json';
    	$options['headers']['Content-Type'] = 'application/json';
    	
	    if ( $this->publicApiKey && $this->privateApiKey ) {
	        $options['headers']['Date'] = $date;
	        $options['headers']['Authorization'] = $this->_signRequest($method, $date, $url);
	    }
	    
	    $params = $call->getQueryParams();
	    if ( $this->locale ) {
	        $params['locale'] = $this->locale;
	    }	    
	    
	    return $this->_httpAdapter->request($method, $url, $params, $options);
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
    private function _signRequest($method, $date, $url)
    {
        $data = $method . "\n" . $date . "\n" . $url . "\n";
        
        $encodedData = base64_encode(hash_hmac('sha1',$this->privateApiKey,$data));
        
        $signature = strtr(self::API_SIGNATURE, array(
                					'{publicApiKey}' => $this->publicApiKey,
                					'{data}' => $encodedData
                                )
                            );
        
        return $signature;
    }
}