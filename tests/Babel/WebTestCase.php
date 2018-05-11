<?php

namespace tests\AbstractBabel\Babel;

use Symfony\Component\BrowserKit\Client;
use Symfony\Component\HttpKernel\Client as KernelClient;

abstract class WebTestCase extends ApplicationTestCase
{
    final protected function createClient() : Client
    {
        return new KernelClient($this->getApp());
    }
}
