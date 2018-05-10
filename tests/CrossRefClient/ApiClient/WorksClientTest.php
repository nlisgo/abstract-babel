<?php

namespace tests\AbstractBabel\CrossRefClient\ApiClient;

use AbstractBabel\CrossRefClient\ApiClient\WorksClient;
use AbstractBabel\CrossRefClient\HttpClient\HttpClient;
use AbstractBabel\CrossRefClient\Result\ArrayResult;
use GuzzleHttp\Promise\FulfilledPromise;
use GuzzleHttp\Psr7\Request;
use PHPUnit\Framework\TestCase;
use tests\AbstractBabel\CrossRefClient\RequestConstraint;

/**
 * @covers \AbstractBabel\CrossRefClient\ApiClient\WorksClient
 */
final class WorksClientTest extends TestCase
{
    private $httpClient;
    /** @var WorksClient */
    private $worksClient;

    /**
     * @before
     */
    protected function setUpClient()
    {
        $this->httpClient = $this->getMockBuilder(HttpClient::class)
            ->getMock();
        $this->worksClient = new WorksClient(
            $this->httpClient,
            ['X-Foo' => 'bar']
        );
    }

    /**
     * @test
     */
    public function it_can_get_a_work()
    {
        $doi = 'doi';

        $request = new Request(
            'GET',
            'works/'.$doi,
            ['X-Foo' => 'bar', 'User-Agent' => 'CrossRefClient']
        );
        $response = new FulfilledPromise(new ArrayResult(['foo' => ['bar', 'baz']]));
        $this->httpClient
            ->expects($this->once())
            ->method('send')
            ->with(RequestConstraint::equalTo($request))
            ->willReturn($response);
        $this->assertSame($response, $this->worksClient->getWork([], $doi));
    }
}
