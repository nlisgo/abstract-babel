<?php

namespace tests\AbstractBabel\CrossRefClient\Exception;

use AbstractBabel\CrossRefClient\Exception\ApiException;
use Exception;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 * @covers \AbstractBabel\CrossRefClient\Exception\ApiException
 */
final class ApiExceptionTest extends TestCase
{
    /**
     * @test
     */
    public function it_requires_a_message()
    {
        $e = new ApiException('foo');
        $this->assertSame('foo', $e->getMessage());
    }

    /**
     * @test
     */
    public function it_has_an_error_code_of_zero()
    {
        $e = new ApiException('foo');
        $this->assertSame(0, $e->getCode());
    }

    /**
     * @test
     */
    public function it_is_an_instance_of_runtime_exception()
    {
        $e = new ApiException('foo');
        $this->assertInstanceOf(RuntimeException::class, $e);
    }

    /**
     * @test
     */
    public function it_may_not_have_a_previous_exception()
    {
        $e = new ApiException('foo');
        $this->assertNull($e->getPrevious());
    }

    /**
     * @test
     */
    public function it_may_have_a_previous_exception()
    {
        $previous = new Exception('bar');
        $e = new ApiException('foo', $previous);
        $this->assertSame($previous, $e->getPrevious());
    }
}
