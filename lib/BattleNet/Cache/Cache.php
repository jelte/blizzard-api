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
 * Interface for cache drivers.
 *
 * @author Jelte Steijaert <jelte AT 4tueel DOT be>
 */
interface Cache
{
    /**
     * Fetches an entry from the cache.
     * 
     * @param string
     * @return string
     */
    function fetch($id);

    /**
     * Check if an entry exists in the cache.
     *
     * @param string
     * @return boolean
     */
    function contains($id);

    /**
     * Saves data into the cache.
     *
     * @param string $id The cache id.
     * @param string $data The cache entry/data.
     * @param int $lifeTime
     * @return boolean
     */
    function save($id, $data, $lifeTime = 0);

    /**
     * Deletes a cache entry.
     * 
     * @param string
     * @return boolean
     */
    function delete($id);
}