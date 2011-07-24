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

namespace BattleNet\Http\Response;

/**
 * Fallback response class.
 *
 * @author 		Jelte Steijaert <jelte AT 4tueel DOT be>
 * @version		0.1.0
 *
 * @abstract
 */
class FallbackResponse
extends AbstractResponse
{
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
        parent::__construct($url, $headers, $response);

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
        $this->setResponseCode(array_shift($headers));

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

    private function setResponseCode($responseHeader)
    {
        preg_match('/^(HTTP\/1\.1)( )([0-9]{3})( )(.*)$/', $responseHeader, $matches);
        $this->responseCode = $matches[3];
        $this->responseMessage = $matches[5];
    }
}