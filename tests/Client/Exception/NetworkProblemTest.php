<?php

namespace tests\AbstractBabel\Client\Exception;

use AbstractBabel\Client\Exception\HttpProblem;
use AbstractBabel\Client\Exception\NetworkProblem;
use GuzzleHttp\Psr7\Request;
use PHPUnit\Framework\TestCase;

/**
 * @covers \AbstractBabel\Client\Exception\NetworkProblem
 */
final class NetworkProblemTest extends TestCase
{
    /**
     * @test
     */
    public function it_is_an_instance_of_http_problem()
    {
        $e = new NetworkProblem('foo', new Request('GET', 'http://www.example.com/'));
        $this->assertInstanceOf(HttpProblem::class, $e);
    }
}
