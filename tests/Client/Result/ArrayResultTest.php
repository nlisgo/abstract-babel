<?php

namespace tests\AbstractBabel\Client\Result;

use AbstractBabel\Client\Result\ArrayResult;
use ArrayAccess;
use ArrayIterator;
use BadMethodCallException;
use Countable;
use IteratorAggregate;
use PHPUnit_Framework_TestCase;

/**
 * @covers \AbstractBabel\Client\Result\ArrayResult
 */
final class ArrayResultTest extends PHPUnit_Framework_TestCase
{
    private $data;
    /** @var ArrayResult */
    private $result;

    /**
     * @before
     */
    protected function setUpResult()
    {
        $this->data = ['foo' => ['bar', 'baz']];
        $this->result = new ArrayResult($this->data);
    }

    /**
     * @test
     */
    public function it_casts_to_any_array()
    {
        $this->assertSame($this->data, $this->result->toArray());
    }

    /**
     * @test
     */
    public function it_can_be_counted()
    {
        $this->assertInstanceOf(Countable::class, $this->result);
        $this->assertSame(count($this->data), $this->result->count());
    }

    /**
     * @test
     */
    public function it_can_be_iterated()
    {
        $this->assertInstanceOf(IteratorAggregate::class, $this->result);
        $this->assertEquals(new ArrayIterator($this->data), $this->result->getIterator());
    }

    /**
     * @test
     */
    public function it_can_be_accessed_like_an_array()
    {
        $this->assertInstanceOf(ArrayAccess::class, $this->result);
        $this->assertTrue($this->result->offsetExists('foo'));
        $this->assertSame($this->data['foo'], $this->result->offsetGet('foo'));
    }

    /**
     * @test
     */
    public function it_is_immutable()
    {
        try {
            $this->result->offsetSet('foo', 'bar');
            $this->fail('Value cannot be adjusted once set');
        } catch (BadMethodCallException $exception) {
            $this->assertTrue(true, 'Value cannot be adjusted once set');
        }
        try {
            $this->result->offsetUnset('foo');
            $this->fail('Value cannot be adjusted once set');
        } catch (BadMethodCallException $exception) {
            $this->assertTrue(true, 'Value cannot be adjusted once set');
        }
    }
}
