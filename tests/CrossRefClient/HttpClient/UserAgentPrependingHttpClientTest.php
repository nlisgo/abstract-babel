<?php

namespace tests\AbstractBabel\CrossRefClient\HttpClient;

use AbstractBabel\CrossRefClient\HttpClient\HttpClient;
use AbstractBabel\CrossRefClient\HttpClient\NotifyingHttpClient;
use AbstractBabel\CrossRefClient\HttpClient\UserAgentPrependingHttpClient;
use GuzzleHttp\Psr7\Request;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Traversable;

/**
 * @covers \AbstractBabel\CrossRefClient\HttpClient\UserAgentPrependingHttpClient
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
