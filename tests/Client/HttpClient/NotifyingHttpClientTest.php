<?php

namespace tests\AbstractBabel\Client\HttpClient;

use AbstractBabel\Client\HttpClient\HttpClient;
use AbstractBabel\Client\HttpClient\NotifyingHttpClient;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use function GuzzleHttp\Promise\promise_for;

/**
 * @covers \AbstractBabel\Client\HttpClient\NotifyingHttpClient
 */
final class NotifyingHttpClientTest extends TestCase
{
    private $originalClient;
    private $client;

    /**
     * @before
     */
    protected function setUpOriginalClient()
    {
        $this->originalClient = $this->getMockBuilder(HttpClient::class)
            ->getMock();
        $this->client = new NotifyingHttpClient($this->originalClient);
    }

    /**
     * @test
     */
    public function it_allows_listeners_to_monitor_requests()
    {
        $request = new Request('GET', 'foo');
        $response = new Response(200);
        $this->originalClient->expects($this->once())
            ->method('send')
            ->with($request)
            ->will($this->returnValue(promise_for($response)));
        $this->sentRequests = [];
        $this->client->addRequestListener(function ($request) {
            $this->sentRequests[] = $request;
        });

        $this->client->send($request);

        $this->assertSame([$request], $this->sentRequests);
    }

    /**
     * @test
     */
    public function it_does_not_propagate_errors_of_listeners()
    {
        $request = new Request('GET', 'foo');

        $this->client->addRequestListener(function ($request) {
            throw new RuntimeException('mocked error in listener');
        });

        $this->client->send($request);
        $this->assertTrue(true);
    }
}
