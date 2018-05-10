<?php

namespace tests\AbstractBabel\CrossRefClient;

use AbstractBabel\CrossRefClient\ApiSdk;
use AbstractBabel\CrossRefClient\Client\Works;
use AbstractBabel\CrossRefClient\HttpClient\HttpClient;
use PHPUnit\Framework\TestCase;

/**
 * @covers \AbstractBabel\CrossRefClient\ApiSdk
 */
final class ApiSdkTest extends TestCase
{
    /**
     * @test
     */
    public function it_creates_a_works_client()
    {
        $this->assertInstanceOf(
            Works::class,
            (new ApiSdk($this->getMockBuilder(HttpClient::class)->getMock()))->works()
        );
    }
}
