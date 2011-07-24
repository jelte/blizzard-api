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

/**
 * Primary Call class that all children source API Calls extend. Provides functionality for
 * filtering the query parameters.
 *
 * @author 		Jelte Steijaert <jelte AT 4tueel DOT be>
 * @version		0.1.0
 *
 * @abstract
 */
abstract class AbstractCall
{
    /**
     * HTTP Method used for this call
     *
     * @access protected
     * @var string
     */
    protected $_method = 'GET';

    /**
     * Path
     * @access protected
     * @var string
     */
    protected $_path;

    /**
     * Storage for the query parameters
     *
     * @access protected
     * @var array
     */
    protected $_queryParameters = array();

    /**
     * Definition of the allowed query paramenters
     *
     * @access protected
     * @var array
     */
    protected $_whitelist = array();

    /**
     * Get the Path.
     * Path variable will be replaces on call.
     *
     * @access public
     * @return string
     */
    public function getPath()
    {
        return $this->_path;
    }

    /**
     * get the Query parameters
     *
     * @access public
     * @return array
     */
    public function getQueryParams()
    {
        return $this->_queryParameters;
    }

    /**
     * set a query parameter
     *
     * @access public
     * @param string $name
     * @param mixed $value
     * @throws ApiException when the $name is not set in the whitelist
     * @return void
     */
    public function setQueryParam($name, $value)
    {
        if ( !in_array($name, $this->_whitelist) ) {
            throw new ApiException(sprintf('Query parameter "%s" not recognized.',$name));
        }
        $this->_queryParameters[$name] = $this->filterQueryParamValue($value);
    }

    /**
     * Get the method
     *
     * @access public
     * @return string
     */
    public function getMethod()
    {
        return $this->_method;
    }

    /**
     * Filters the query parameter values,
     * if the value is an array the each value of the array will be filtered and then implode seperated by a ,
     * the value will be url encoded according to blizzard usage
     *
     * @access protected
     * @param mixed $value
     * @return string
     */
    protected function filterQueryParamValue($value)
    {
        if ( is_array($value) ) {
            foreach ( $value as $key => $val ) {
                $value[$key] = $this->filterQueryParamValue($val);
            }
            $value = implode(',',$value);
        }
        return rawurlencode($value);
    }
}