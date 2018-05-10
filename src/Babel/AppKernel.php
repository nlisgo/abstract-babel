<?php

namespace AbstractBabel\Babel;

use AbstractBabel\Babel\Controller\BabelController;
use AbstractBabel\CrossRefClient\ApiSdk as CrossRefSdk;
use Csa\GuzzleHttp\Middleware\Cache\MockMiddleware;
use AbstractBabel\CrossRefClient\HttpClient\BatchingHttpClient as CrossRefBatchingHttpClient;
use AbstractBabel\CrossRefClient\HttpClient\Guzzle6HttpClient as CrossRefGuzzle6HttpClient;
use AbstractBabel\CrossRefClient\HttpClient\NotifyingHttpClient as CrossRefNotifyingHttpClient;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use Pimple\Exception\UnknownIdentifierException;
use Psr\Container\ContainerInterface;
use Silex\Application;
use Silex\Provider\HttpFragmentServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\TerminableInterface;
use tests\AbstractBabel\Babel\InMemoryStorageAdapter;

final class AppKernel implements ContainerInterface, HttpKernelInterface, TerminableInterface
{
    private $app;

    public function __construct(string $environment = 'dev')
    {
        $configFile = __DIR__.'/../../config.php';
        $config = array_merge(
            'test' !== $environment && file_exists($configFile) ? require $configFile : [],
            require __DIR__."/../../config/{$environment}.php"
        );

        $this->app = new Application([
            'debug' => $config['debug'] ?? false,
            'api.requests_batch' => $config['api_requests_batch'] ?? 10,
            'crossref' => ($config['crossref'] ?? []) + [
                'api_url' => 'https://api.crossref.org/',
            ],
            'mock' => $config['mock'] ?? false,
        ]);

        $this->app->register(new ServiceControllerServiceProvider());

        if ($this->app['debug']) {
            $this->app->register(new HttpFragmentServiceProvider());
            $this->app->register(new TwigServiceProvider());
        }

        $this->app['crossref.guzzle.handler'] = function () {
            return HandlerStack::create();
        };

        if ($this->app['mock']) {
            $this->app['guzzle.mock.in_memory_storage'] = function () {
                return new InMemoryStorageAdapter();
            };

            $this->app['crossref.guzzle.mock'] = function () {
                return new MockMiddleware($this->app['guzzle.mock.in_memory_storage'], 'replay');
            };

            $this->app->extend('crossref.guzzle.handler', function (HandlerStack $stack) {
                $stack->push($this->app['crossref.guzzle.mock']);

                return $stack;
            });
        }

        $this->app['crossref.guzzle'] = function () {

            return new Client([
                'base_uri' => $this->app['crossref']['api_url'],
                'connect_timeout' => 0.5,
                'decode_content' => 'gzip',
                'handler' => $this->app['crossref.guzzle.handler'],
                'timeout' => 0.9,
            ]);
        };

        $this->app['crossref.sdk'] = function () {
            $notifyingHttpClient = new CrossRefNotifyingHttpClient(
                new CrossRefBatchingHttpClient(
                    new CrossRefGuzzle6HttpClient(
                        $this->app['crossref.guzzle']
                    ),
                    $this->app['api.requests_batch']
                )
            );

            return new CrossRefSdk($notifyingHttpClient);
        };

        $this->app['controllers.babel'] = function () {
            return new BabelController($this->app['crossref.sdk']);
        };

        $this->app->get('/babel', 'controllers.babel:babelAction');

        $this->app->after(function (Request $request, Response $response) {
            if ($response->isCacheable()) {
                $response->headers->set('ETag', md5($response->getContent()));
                $response->isNotModified($request);
            }
        });
    }

    public function handle(Request $request, $type = self::MASTER_REQUEST, $catch = true) : Response
    {
        return $this->app->handle($request, $type, $catch);
    }

    public function terminate(Request $request, Response $response)
    {
        $this->app->terminate($request, $response);
    }

    public function get($id)
    {
        if (!isset($this->app[$id])) {
            throw new UnknownIdentifierException($id);
        }

        return $this->app[$id];
    }

    public function has($id) : bool
    {
        return isset($this->app[$id]);
    }
}
