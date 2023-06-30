<?php

declare(strict_types=1);

/**
 * Tests for the MemoizeAware Trait.
 *
 * @since 0.2.0
 * @author Glynn Quelch <glynn.quelch@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 * @package PinkCrab\Memoize
 */

namespace PinkCrab\Memoize\Tests;

use Exception;
use PHPUnit\Framework\TestCase;
use PinkCrab\Memoize\Tests\HasMemoizeAware;

class TestMemoizeAware extends TestCase
{

    /**
     * The class which hold
     *
     * @var HasMemoizeAware
     */
    protected static $class;

    /**
     * Create isntance of test class.
     *
     * @return void
     */
    public static function setUpBeforeClass(): void
    {
        static::$class = new HasMemoizeAware();
    }

    /**
     * Clears the call log after each test.
     * Throws an Exception if not cleared
     *
     * @return void
     * @throws Exception
     */
    protected function tearDown(): void
    {
        static::$class::clearLog();
        if (static::$class::countLog() !== 0) {
            throw new Exception("CALLED LOG NOT CLEARED!");
        }
    }

    /**
     * Test returns a result from the callback with new call signature.
     *
     * @return void
     */
    public function testReturnsResult(): void
    {
        // Do the operation.
        $results = static::$class->getThing(1, 2, 'string value');

        // Check the results are as expected and that the function was called.
        $this->assertEquals('string value', $results->param3);
        $this->assertEquals(1, static::$class::countLog());
    }

    /**
     * Test a repeat call is from the cache.
     *
     * @return void
     */
    public function testRepeateCallFromCache(): void
    {
        // First call
        $first = static::$class->getThing('alpha', 2, 'charlie');

        // Do the call and check it logged
        $this->assertEquals('charlie', $first->param3);
        $this->assertEquals(1, static::$class::countLog());

        // Call with the same params and check not called again.
        $second = static::$class->getThing('alpha', 2, 'charlie');
        $this->assertEquals('charlie', $second->param3);
        $this->assertEquals(1, static::$class::countLog());
    }

    /**
     * Test can create hash from basic scalar types
     * Int, Float, String, Bool, Array, Object
     */
    public function testCanCreateHashFromScalarTypes(): void
    {
        // First call
        $a = static::$class->getThing(1, 2.2, 'three');
        $b = static::$class->getThing(true, ['true' => 'true'], (object)['true' => 'true']);
        // Second call
        $a_2 = static::$class->getThing(1, 2.2, 'three');
        $b_2 = static::$class->getThing(true, ['true' => 'true'], (object)['true' => 'true']);

        // Check only called twice and they match.
        $this->assertEquals(2, static::$class::countLog());
        $this->assertSame($a, $a_2);
        $this->assertSame($b, $b_2);
    }

    /**
     * Test throws exception if none serialized value passed.
     *
     * @return void
     */
    public function testThrowsExceptionIfNoneSerializedClassHashed(): void
    {
        $this->expectException((Exception::class));
        $a = static::$class->getThing(1, 2.2, function () {
            return 3;
        });
    }

    /**
     * Test numerical value are treated as unique between types.
     *
     * @return void
     */
    public function testSimilarTypes(): void
    {
        static::$class->getThing(1, (float) 1, '1');
        static::$class->getThing(1, (float) '1', 1);
        static::$class->getThing('1', 1, (float) 1);
        static::$class->getThing(1, '1', (float) 1);
        static::$class->getThing((float) 1, '1', 1);
        static::$class->getThing((float) 1, 1, '1');

        // Test its called 6 times.
        $this->assertEquals(6, static::$class::countLog());
    }
}
