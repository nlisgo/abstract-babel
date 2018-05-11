<?php

namespace tests\AbstractBabel\CrossRefClient;

use AbstractBabel\CrossRefClient\Client\Works;
use AbstractBabel\Client\HttpClient\HttpClient;
use AbstractBabel\CrossRefClient\CrossRefSdk;
use PHPUnit\Framework\TestCase;

/**
 * @covers \AbstractBabel\CrossRefClient\CrossRefSdk
 */
final class CrossRefSdkTest extends TestCase
{
    /**
     * @test
     */
    public function it_creates_a_works_client()
    {
        $this->assertInstanceOf(
            Works::class,
            (new CrossRefSdk($this->getMockBuilder(HttpClient::class)->getMock()))->works()
        );
    }
}
