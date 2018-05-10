<?php

namespace tests\AbstractBabel\CrossRefClient\Exception;

use AbstractBabel\CrossRefClient\Exception\HttpProblem;
use AbstractBabel\CrossRefClient\Exception\NetworkProblem;
use GuzzleHttp\Psr7\Request;
use PHPUnit\Framework\TestCase;

/**
 * @covers \AbstractBabel\CrossRefClient\Exception\NetworkProblem
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
