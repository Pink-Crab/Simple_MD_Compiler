<?php

declare(strict_types=1);

/**
 * Basic Memoize object cache trait.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * @author Glynn Quelch <glynn.quelch@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 * @package PinkCrab\Core
 */

namespace PinkCrab\Memoize;

/**
 * Adds memoization caching.
 *
 * @since 0.2.0
 */
trait Memoizable
{

    /**
     * Object cache for common queries.
     *
     * @var array
     */
    protected static $memoizeCacheStore = array();

    /**
     * If the value doesnt exist, execute and store, then return.
     *
     * @param string $hash
     * @param callable $fetch
     * @return mixed
     */
    protected function memoize(string $hash, callable $fetch)
    {
        if (!array_key_exists($hash, self::$memoizeCacheStore)) {
            self::$memoizeCacheStore[$hash] = $fetch();
        }
        return self::$memoizeCacheStore[$hash] ?? null;
    }

    /**
     * Generates a (mostly) unique hash based on any combination of parts.
     *
     * @param mixed $parts
     * @return string
     */
    protected function generateHash(...$parts): string
    {
        return md5((string) join($this->mapHashParts($parts)));
    }

    /**
     * Ensures that all parts are cast strings.
     * Will serialize object & arrays.
     *
     * @param array $parts
     * @return array
     * @throws Exception If unserializable part.I
     */
    protected function mapHashParts(array $parts): array
    {
        return array_map(
            function ($e) {
                return serialize($e);
            },
            $parts
        );
    }

    /**
     * Flush the objet cache.
     *
     * @return void
     */
    protected function flushMemoize()
    {
        self::$memoizeCacheStore = array();
    }
}
