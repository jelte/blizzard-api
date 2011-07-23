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

namespace BattleNet\Api\Wow\Call;


use BattleNet\Api\ApiException;
use BattleNet\Api\AbstractCall;

/**
 * Call for the WoW Character Api
 *
 * @author 		Jelte Steijaert <jelte AT 4tueel DOT be>
 * @version		0.1.0
 */
class CharacterCall
    extends AbstractCall
{
    /**
     * {@inheritdoc}
     */
    protected $_path = 'character/{realm}/{name}';
            
    /**
     * name of the realm
     * 
     * @access protected
     * @var string
     */
    protected $realm;
    
    /**
     * name of the guild
     * 
     * @access protected
     * @var string
     */
    protected $name;
            
    /**
     * {@inheritdoc}
     */
    protected $_whitelist = array('fields');
    
    /**
     * allowed fields parameters
     * 
	 * @access protected
     * @var array
     * @see http://blizzard.github.com/api-wow-docs/#id3525224
     */
    protected $_fieldsWhitelist = array('guild','stats','talents','items','reputation','titles',
    									'professions','appearance','companions','mounts','pets',
    									'achievements','progression');
    
    /**
     * Constructor 
     * 
     * @param $realm
     * @param $name
     * @param $fields
     * @constructor
     * @return void
     */
    public function __construct($realm, $name, array $fields = array())
    {
        $this->setRealm($realm);
        $this->setName($name);
        $this->setFields($fields);
    }
    
	/**
     * {@inheritdoc}
     */
    public function getPath()
    {
        $path = str_replace('{realm}',$this->realm, $this->_path);
        $path = str_replace('{name}',$this->name, $path);
        
        return $path;
    }

    /**
     * Filter and set the realm name
     * 
     * @access public
     * @param $realm
     * @return void
     */
    public function setRealm($realm)
    {
        $this->realm = $this->filterQueryParamValue($realm);
    }
        
    /**
     * Filter and set the guild name
     * 
     * @access public
     * @param $name
     * @return void
     */
    public function setName($name)
    {
        $this->name = $this->filterQueryParamValue($name);
    }
        
    /**
     * Set the additional fields
     * 
     * @access public
     * @param $fields
     * @return void
     * @throws ApiException when a field option is not recognized
     */
    public function setFields($fields)
    {
        foreach ( $fields as $field ) {
            if ( !in_array($field, $this->_fieldsWhitelist) ) {
                throw new ApiException(sprintf('Field option "%s" not recognized.',$field));
            }                
        }
        $this->setQueryParam('fields',$fields);
    }
}