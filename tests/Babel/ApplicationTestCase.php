<?php

namespace tests\AbstractBabel\Babel;

use AbstractBabel\CrossRefClient\CrossRefSdk;
use Csa\GuzzleHttp\Middleware\Cache\Adapter\StorageAdapterInterface;
use AbstractBabel\Babel\AppKernel;
use function GuzzleHttp\json_encode;

abstract class ApplicationTestCase extends ApiTestCase
{
    /** @var AppKernel */
    private $app;

    /**
     * @before
     */
    final public function setUpApp()
    {
        $this->app = new AppKernel('test');
    }

    final protected function getApp() : AppKernel
    {
        return $this->app;
    }

    final protected function getCrossRefSdk() : CrossRefSdk
    {
        return $this->app->get('crossref.sdk');
    }

    final protected function getMockStorage() : StorageAdapterInterface
    {
        return $this->app->get('guzzle.mock.in_memory_storage');
    }

    final protected function assertJsonStringEqualsJson(array $expectedJson, string $actualJson, $message = '')
    {
        $this->assertJsonStringEqualsJsonString(json_encode($expectedJson), $actualJson, $message);
    }
}
