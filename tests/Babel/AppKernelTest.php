<?php

namespace tests\AbstractBabel\Babel;

/**
 * @covers \AbstractBabel\Babel\AppKernel
 */
final class AppKernelTest extends WebTestCase
{
    /**
     * @test
     */
    public function it_returns_200_pong_when_the_application_is_correctly_setup()
    {
        $client = static::createClient();

        $client->request('GET', '/ping');

        $this->assertSame(200, $client->getResponse()->getStatusCode(), var_export($client->getResponse()->getContent(), true));
        $this->assertSame('text/plain; charset=UTF-8', $client->getResponse()->headers->get('Content-Type'));
        $this->assertSame('pong', $client->getResponse()->getContent());
        $this->assertFalse($client->getResponse()->isCacheable());
    }
}
