<?php

use AbstractBabel\Babel\AppKernel;
use Symfony\Component\HttpFoundation\Request;

require_once __DIR__.'/../vendor/autoload.php';

$app = new AppKernel(getenv('ENVIRONMENT_NAME') ?: 'dev');

$request = Request::createFromGlobals();

$response = $app->handle($request);
$response->send();

$app->terminate($request, $response);
