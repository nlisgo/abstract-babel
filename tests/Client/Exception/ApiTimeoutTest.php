<?php

namespace tests\AbstractBabel\Client\Exception;

use AbstractBabel\Client\Exception\ApiTimeout;
use AbstractBabel\Client\Exception\NetworkProblem;
use GuzzleHttp\Psr7\Request;
use PHPUnit\Framework\TestCase;

/**
 * @covers \AbstractBabel\Client\Exception\ApiTimeout
 */
final class ApiTimeoutTest extends TestCase
{
    /**
     * @test
     */
    public function it_is_an_instance_of_network_problem()
    {
        $e = new ApiTimeout('foo', new Request('GET', 'http://www.example.com/'));
        $this->assertInstanceOf(NetworkProblem::class, $e);
    }
}
