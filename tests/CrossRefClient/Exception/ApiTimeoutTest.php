<?php

namespace tests\AbstractBabel\CrossRefClient\Exception;

use AbstractBabel\CrossRefClient\Exception\ApiTimeout;
use AbstractBabel\CrossRefClient\Exception\NetworkProblem;
use GuzzleHttp\Psr7\Request;
use PHPUnit_Framework_TestCase;

/**
 * @covers \AbstractBabel\CrossRefClient\Exception\ApiTimeout
 */
final class ApiTimeoutTest extends PHPUnit_Framework_TestCase
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
