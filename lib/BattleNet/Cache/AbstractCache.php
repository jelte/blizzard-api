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
 * Base class for cache driver implementations.
 *
 * @author  Jelte Steijaert <jelte AT 4tueel DOT be>
 * @abstract
 */
abstract class AbstractCache implements Cache
{
    /**
     * @access private 
     * @var string The namespace to prefix all cache ids with 
     */
    private $_namespace = '';

    /**
     * Set the namespace to prefix all cache ids with.
     *
     * @access public
     * @param string $namespace
     * @return void
     */
    public function setNamespace($namespace)
    {
        $this->_namespace = (string) $namespace;
    }

    /**
     * {@inheritdoc}
     */
    public function fetch($id)
    {
        return $this->_doFetch($this->_getNamespacedId($id));
    }

    /**
     * {@inheritdoc}
     */
    public function contains($id)
    {
        return $this->_doContains($this->_getNamespacedId($id));
    }

    /**
     * {@inheritdoc}
     */
    public function save($id, $data, $lifeTime = 0)
    {
        return $this->_doSave($this->_getNamespacedId($id), $data, $lifeTime);
    }

    /**
     * {@inheritdoc}
     */
    public function delete($id)
    {
        return $this->_doDelete($this->_getNamespacedId($id));
    }

   	/**
     * Prefix the passed id with the configured namespace value
     *
     * @access private
     * @param string $id
     * @return string $id
     */
    private function _getNamespacedId($id)
    {
        return $this->_namespace . $id;
    }

    /**
     * Fetches an entry from the cache.
     *
     * @abstract
     * @access protected
     * @param string $id
     * @return string
     */
    abstract protected function _doFetch($id);

    /**
     * Checks if an entry exists in the cache.
     *
     * @abstract
     * @access protected
     * @param string $id
     * @return boolean
     */
    abstract protected function _doContains($id);

    /**
     * Saves data into the cache.
     *
     * @abstract
     * @access protected
     * @param string $id
     * @param string $data
     * @param int $lifeTime
     * @return boolean
     */
    abstract protected function _doSave($id, $data, $lifeTime = false);

    /**
     * Deletes a cache entry.
     *
     * @abstract
     * @access protected
     * @param string $id
     * @return boolean
     */
    abstract protected function _doDelete($id);
}