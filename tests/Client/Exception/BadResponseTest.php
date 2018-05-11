<?php

namespace tests\AbstractBabel\Client\Exception;

use AbstractBabel\Client\Exception\BadResponse;
use AbstractBabel\Client\Exception\HttpProblem;
use Exception;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

/**
 * @covers \AbstractBabel\Client\Exception\BadResponse
 */
final class BadResponseTest extends TestCase
{
    /**
     * @test
     */
    public function it_requires_a_message()
    {
        $e = new BadResponse('foo', new Request('GET', 'http://www.example.com/'), new Response());
        $this->assertSame('foo', $e->getMessage());
    }

    /**
     * @test
     */
    public function it_requires_a_request()
    {
        $request = new Request('GET', 'http://www.example.com/');
        $e = new BadResponse('foo', $request, new Response());
        $this->assertSame($request, $e->getRequest());
    }

    /**
     * @test
     */
    public function it_requires_a_response()
    {
        $response = new Response();
        $e = new BadResponse('foo', new Request('GET', 'http://www.example.com/'), $response);
        $this->assertSame($response, $e->getResponse());
    }

    /**
     * @test
     */
    public function it_is_an_instance_of_http_problem()
    {
        $e = new BadResponse('foo', new Request('GET', 'http://www.example.com/'), new Response());
        $this->assertInstanceOf(HttpProblem::class, $e);
    }

    /**
     * @test
     */
    public function it_may_not_have_a_previous_exception()
    {
        $e = new BadResponse('foo', new Request('GET', 'http://www.example.com/'), new Response());
        $this->assertNull($e->getPrevious());
    }

    /**
     * @test
     */
    public function it_may_have_a_previous_exception()
    {
        $previous = new Exception('bar');
        $e = new BadResponse('foo', new Request('GET', 'http://www.example.com/'), new Response(), $previous);
        $this->assertSame($previous, $e->getPrevious());
    }
}
