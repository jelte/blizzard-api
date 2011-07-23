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

use \Memcache;

/**
 * Memcache cache driver.
 *
 * @author Jelte Steijaert <jelte AT 4tueel DOT be>
 */
class MemcacheCache extends AbstractCache
{
    /**
     * @var Memcache
     */
    private $_memcache;

    /**
     * Sets the memcache instance to use.
     *
     * @param Memcache $memcache
     */
    public function setMemcache(Memcache $memcache)
    {
        $this->_memcache = $memcache;
    }

    /**
     * Gets the memcache instance used by the cache.
     *
     * @return Memcache
     */
    public function getMemcache()
    {
        return $this->_memcache;
    }

    /**
     * {@inheritdoc}
     */
    protected function _doFetch($id)
    {
        return $this->_memcache->get($id);
    }

    /**
     * {@inheritdoc}
     */
    protected function _doContains($id)
    {
        return (bool) $this->_memcache->get($id);
    }

    /**
     * {@inheritdoc}
     */
    protected function _doSave($id, $data, $lifeTime = 0)
    {
        return $this->_memcache->set($id, $data, 0, (int) $lifeTime);
    }

    /**
     * {@inheritdoc}
     */
    protected function _doDelete($id)
    {
        return $this->_memcache->delete($id);
    }
}