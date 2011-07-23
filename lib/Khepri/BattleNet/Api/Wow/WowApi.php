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

namespace Khepri\BattleNet\Api\Wow;

use Khepri\BattleNet\Api\AbstractApi;

use Khepri\BattleNet\Api\Wow\Call\CharacterCall;
use Khepri\BattleNet\Api\Wow\Call\Data\ItemCall;
use Khepri\BattleNet\Api\Wow\Call\Data\Character\ClassesCall;
use Khepri\BattleNet\Api\Wow\Call\Data\Character\RacesCall;
use Khepri\BattleNet\Api\Wow\Call\GuildCall;
use Khepri\BattleNet\Api\Wow\Call\Realm\StatusCall as RealmStatusCall;


/**
 * Concrete interface for Wow Api
 *
 * @author 		Jelte Steijaert <jelte AT 4tueel DOT be>
 * @version		0.1.0
 */
class WowApi
    extends AbstractApi
{
    /**
     * set the game to WoW
     * 
     * @access protected
     * @var string
     */
    protected $game = 'wow';
    
    /**
     * {@inheritdoc}
     */
    protected $_regionWhitelist = array(
                                        'us' => array('en_US','es_MX'),
                                        'eu' => array('en_GB','es_ES','fr_FR','ru_RU','de_DE'),
                                        'kr' => array('ko_KR'),
                                        'tw' => array('zh_TW'),
                                        'cn' => array('zh_CN')
                                    );
                                        
    /**
     * Prepare and execute a Character call
     * 
     * @access public
     * @param string $realm
     * @param string $name
     * @param array $fields
     * @param boolean $resultAsArray
     * @return mixed
     */
    public function getCharacter($realm, $name, array $fields = array(), $resultAsArray = false)
    {
        return $this->request(new CharacterCall($realm, $name, $fields))->getData($resultAsArray);
    }
    
    /**
     * Prepare and execute a Guild call
     * 
     * @access public
     * @param string $realm
     * @param string $name
     * @param array $fields
     * @param boolean $resultAsArray
     * @return mixed
     */
    public function getGuild($realm, $name, array $fields = array(), $resultAsArray = false)
    {
        return $this->request(new GuildCall($realm, $name, $fields))->getData();
    }
        
    /**
     * Prepare and execute a Character call
     * 
     * @access public
     * @param string $realm
     * @param boolean $resultAsArray
     * @return mixed
     */
    public function getRealmStatus($realm = null, $resultAsArray = false)
    {
        return $this->request(new RealmStatusCall($realm))->getData();
    }

    /**
     * Prepare and execute a Races call
     * 
     * @access public
     * @param boolean $resultAsArray
     * @return mixed
     */    
    public function getRaces($resultAsArray = false)
    {
        return $this->request(new RacesCall())->getData();
    }
    
    /**
     * Prepare and execute a Classes call
     * 
     * @access public
     * @param boolean $resultAsArray
     * @return mixed
     */    
    public function getClasses($resultAsArray = false)
    {
        return $this->request(new ClassesCall())->getData();
    }
        
    /**
     * Prepare and execute an Item call
     * 
     * @access public
     * @param boolean $resultAsArray
     * @return mixed
     */    
    public function getItem($itemid, $resultAsArray = false)
    {
        return $this->request(new ItemCall($itemid))->getData();
    }
}