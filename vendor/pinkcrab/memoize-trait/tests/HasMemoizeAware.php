<?php

declare(strict_types=1);

/**
 * Helper class for testing the MemoizeAware trait.
 *
 * @since 0.2.0
 * @author Glynn Quelch <glynn.quelch@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 * @package PinkCrab\Memoize
 */

namespace PinkCrab\Memoize\Tests;

use PinkCrab\Memoize\Memoizable;

class HasMemoizeAware
{

    use Memoizable;

    /**
     * Log of all memoize functions called.
     *
     * @var array
     */
    protected static $calledLog = [];

    /**
     * Counts the items in the call log.
     *
     * @return integer
     */
    public static function countLog(): int
    {
        return count(self::$calledLog);
    }

    /**
     * Simulates an operation
     *
     * @param mixed $param1
     * @param mixed $param2
     * @param mixed $param3
     * @return object
     */
    public function getThing($param1, $param2, $param3): object
    {
        return $this->memoize(
            $this->generateHash($param1, $param2, $param3),
            function () use ($param1, $param2, $param3): object {
                return $this->doGetResults($param1, $param2, $param3);
            }
        );
    }

    /**
     * Clears the called log.
     *
     * @return void
     */
    public static function clearLog(): void
    {
        self::$calledLog = [];
    }

    /**
     * Mockers doing an operation and returning an object of its args.
     * Makes a record in the calledLog.
     *
     * @param mixed $param1
     * @param mixed $param2
     * @param mixed $param3
     * @return object
     */
    public function doGetResults($param1, $param2, $param3): object
    {
        $result = (object) [
            'param1' => $param1,
            'param2' => $param2,
            'param3' => $param3,
        ];

        self::$calledLog[] = $result;

        return $result;
    }
}
