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

use BattleNet\Api\AbstractCall;
use InvalidArgumentException;

/**
 * Call for the WoW Quest Api
 *
 * @author 		Jelte Steijaert <jelte AT 4tueel DOT be>
 * @version		0.1.0
 */
class QuestCall
extends AbstractCall
{
    /**
     * {@inheritdoc}
     */
    protected $_path = 'quest/{questid}';

    /**
     * Id of the requested quest
     *
     * @access protected
     * @var integer
     */
    protected $questid;

    /**
     * Constructor
     *
     * @access public
     * @param integer $questid
     * @return void
     */
    public function __construct($questid)
    {
        $this->setQuestid($questid);
    }

    /**
     * {@inheritdoc}
     */
    public function getPath()
    {
        return str_replace('{questid}',$this->questid, $this->_path);
    }

    /**
     * Set the questid
     *
     * @access public
     * @param $questid
     * @throws ApiException when the questid is empty or not numeric
     */
    public function setQuestid($questid)
    {
        if (empty($questid) || !is_numeric($questid)) {
            throw new InvalidArgumentException(sprintf('Item ID "%s" invalid for %s.', $questid, __CLASS__));
        }
        $this->questid = $questid;
    }
}