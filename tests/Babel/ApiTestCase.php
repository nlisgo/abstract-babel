<?php

namespace tests\AbstractBabel\Babel;

use AbstractBabel\CrossRefClient\CrossRefSdk;
use Csa\GuzzleHttp\Middleware\Cache\Adapter\StorageAdapterInterface;
use AbstractBabel\Babel\AppKernel;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use function GuzzleHttp\json_encode;

abstract class ApiTestCase extends TestCase
{
    use HasDiactorosFactory;

    abstract protected function getApp() : AppKernel;

    abstract protected function getCrossRefSdk() : CrossRefSdk;

    abstract protected function getMockStorage() : StorageAdapterInterface;

    final protected function mockCrossRefWorksNotFound(
        string $doi,
        array $headers = []
    ) {
        $this->getMockStorage()->save(
            new Request(
                'GET',
                "https://api.crossref.org/works/$doi",
                $headers
            ),
            new Response(
                404,
                ['Content-Type' => 'text/plain'],
                'Resource not found.'
            )
        );
    }

    final protected function mockCrossRefWorksCall(
        string $doi
    ) {
        $json = [
            'status' => 'ok',
            'message-type' => 'work',
            'message-version' => '1.0.0',
            'message' => [
                'abstract' => 'abstract',
            ],
        ];

        $this->getMockStorage()->save(
            new Request(
                'GET',
                "https://api.crossref.org/works/$doi"
            ),
            new Response(
                200,
                [],
                json_encode($json)
            )
        );
    }
}
