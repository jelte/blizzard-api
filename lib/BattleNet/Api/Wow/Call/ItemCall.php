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
 * Call for the WoW Item Api
 *
 * @author 		Jelte Steijaert <jelte AT 4tueel DOT be>
 * @version		0.1.0
 */
class ItemCall
extends AbstractCall
{
    /**
     * {@inheritdoc}
     */
    protected $_path = 'item/{itemid}';

    /**
     * Id of the requested item
     *
     * @access protected
     * @var integer
     */
    protected $itemid;

    /**
     * Constructor
     *
     * @access public
     * @param integer $itemid
     * @return void
     */
    public function __construct($itemid)
    {
        $this->setItemid($itemid);
    }

    /**
     * {@inheritdoc}
     */
    public function getPath()
    {
        return str_replace('{itemid}',$this->itemid, $this->_path);
    }

    /**
     * Set the itemid
     *
     * @access public
     * @param $itemid
     * @throws ApiException when the itemid is empty or not numeric
     */
    public function setItemid($itemid)
    {
        if (empty($itemid) || !is_numeric($itemid)) {
            throw new InvalidArgumentException(sprintf('Item ID "%s" invalid for %s.', $itemid, __CLASS__));
        }
        $this->itemid = $itemid;
    }
}