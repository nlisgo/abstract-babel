<?php

namespace tests\AbstractBabel\Client\HttpClient;

use AbstractBabel\Client\HttpClient\HttpClient;
use AbstractBabel\Client\HttpClient\NotifyingHttpClient;
use AbstractBabel\Client\HttpClient\UserAgentPrependingHttpClient;
use GuzzleHttp\Psr7\Request;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Traversable;

/**
 * @covers \AbstractBabel\Client\HttpClient\UserAgentPrependingHttpClient
 */
final class UserAgentPrependingHttpClientTest extends TestCase
{
    private $originalClient;
    private $requests;

    /**
     * @before
     */
    protected function setUpOriginalClient()
    {
        $this->requests = [];

        $this->originalClient = new NotifyingHttpClient($this->getMockBuilder(HttpClient::class)->getMock());

        $this->originalClient->addRequestListener(function (RequestInterface $request) {
            $this->requests[] = $request;
        });
    }

    /**
     * @test
     * @dataProvider userAgentProvider
     */
    public function it_sets_a_user_agent(string $existing = null, string $input, string $expected)
    {
        $request = new Request('GET', 'foo', ['User-Agent' => $existing]);

        $client = new UserAgentPrependingHttpClient($this->originalClient, $input);

        $client->send($request)->wait();

        $this->assertSame($expected, $this->requests[0]->getHeaderLine('User-Agent'));
    }

    public function userAgentProvider() : Traversable
    {
        yield 'sets when empty' => [null, 'foo', 'foo'];
        yield 'prepends to existing' => ['bar', 'foo', 'foo bar'];
    }
}
