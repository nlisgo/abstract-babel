<?php

namespace AbstractBabel\Babel;

use AbstractBabel\Babel\Controller\BabelController;
use AbstractBabel\Babel\Controller\HomeController;
use AbstractBabel\CrossRefClient\CrossRefSdk;
use AbstractBabel\TranslateClient\TranslateSdk;
use Aws\Translate\TranslateClient;
use Csa\GuzzleHttp\Middleware\Cache\MockMiddleware;
use AbstractBabel\Client\HttpClient\BatchingHttpClient;
use AbstractBabel\Client\HttpClient\Guzzle6HttpClient;
use AbstractBabel\Client\HttpClient\NotifyingHttpClient;
use eLife\ApiProblem\Silex\ApiProblemProvider;
use eLife\Ping\Silex\PingControllerProvider;
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
            'aws' => ($config['aws'] ?? []) + [
                'credential_file' => true,
                'region' => 'eu-west-1',
            ],
            'mock' => $config['mock'] ?? false,
        ]);

        $this->app->register(new ApiProblemProvider());
        $this->app->register(new PingControllerProvider());
        $this->app->register(new ServiceControllerServiceProvider());
        $this->app->register(new TwigServiceProvider(), [
            'twig.path' => __DIR__.'/views',
        ]);

        $this->app['api_problem.factory.include_exception_details'] = $config['api_problem']['factory']['include_exception_details'] ?? $this->app['debug'];

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
                'connect_timeout' => 2.5,
                'decode_content' => 'gzip',
                'handler' => $this->app['crossref.guzzle.handler'],
                'timeout' => 2.9,
            ]);
        };

        $this->app['crossref.sdk'] = function () {
            $notifyingHttpClient = new NotifyingHttpClient(
                new BatchingHttpClient(
                    new Guzzle6HttpClient(
                        $this->app['crossref.guzzle']
                    ),
                    $this->app['api.requests_batch']
                )
            );

            return new CrossRefSdk($notifyingHttpClient);
        };

        $this->app['aws.translate'] = function () {
            $config = [
                'version' => $this->app['aws']['version'] ?? '2017-07-01',
                'region' => $this->app['aws']['region'],
            ];

            if (!isset($this->app['aws']['credential_file']) || $this->app['aws']['credential_file'] === false) {
                $config['credentials'] = [
                    'key' => $this->app['aws']['key'],
                    'secret' => $this->app['aws']['secret'],
                ];
            }

            return new TranslateClient($config);
        };

        $this->app['translate.sdk'] = function () {
            return new TranslateSdk($this->app['aws.translate']);
        };

        $this->app['controllers.babel'] = function () {
            return new BabelController($this->app['crossref.sdk'], $this->app['translate.sdk']);
        };

        $this->app->get('/babel', 'controllers.babel:babelAction')
            ->bind('babel');

        $this->app->get('/', function () {
            return 'Home page';
        })->bind('home');

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
