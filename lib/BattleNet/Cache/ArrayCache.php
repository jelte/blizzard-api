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
namespace BattleNet\Cache;

/**
 * Array cache driver.
 *
 * @author Jelte Steijaert <jelte AT 4tueel DOT be>
 */
class ArrayCache extends AbstractCache
{
    /**
     * @var array $data
     */
    private $data = array();

    /**
     * {@inheritdoc}
     */
    protected function _doFetch($id)
    {
        if (isset($this->data[$id])) {
            return $this->data[$id];
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    protected function _doContains($id)
    {
        return isset($this->data[$id]);
    }

    /**
     * {@inheritdoc}
     */
    protected function _doSave($id, $data, $lifeTime = 0)
    {
        $this->data[$id] = $data;

        return true;
    }

    /**
     * {@inheritdoc}
     */
    protected function _doDelete($id)
    {
        unset($this->data[$id]);

        return true;
    }
}