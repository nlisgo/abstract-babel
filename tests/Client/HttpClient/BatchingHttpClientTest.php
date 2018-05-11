<?php

namespace tests\AbstractBabel\Client\HttpClient;

use AbstractBabel\Client\HttpClient\BatchingHttpClient;
use AbstractBabel\Client\HttpClient\HttpClient;
use Eris\TestTrait;
use GuzzleHttp\Promise\Promise;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use function Eris\Generator\bind;
use function Eris\Generator\choose;
use function Eris\Generator\constant;
use function Eris\Generator\map;
use function Eris\Generator\tuple;
use function Eris\Generator\vector;

/**
 * @covers \AbstractBabel\Client\HttpClient\BatchingHttpClient
 */
final class BatchingHttpClientTest extends TestCase
{
    use TestTrait;

    public function testRandomSequenceOfSendAndWaits()
    {
        $originalClient = $this->getMockBuilder(HttpClient::class)
            ->getMock();
        $originalClient
            ->expects($this->any())
            ->method('send')
            ->will($this->returnCallback(function ($request) {
                $promise = new Promise(function () use (&$promise, $request) {
                    $response = new Response(200, [], $request->getRequestTarget());
                    $promise->resolve($response);
                });

                return $promise;
            }));
        $this
            ->forAll(
                choose(1, 100),
                bind(
                    map(
                        function ($requests) {
                            $steps = [];
                            foreach (range(0, $requests) as $i) {
                                $steps[] = ['action' => 'send', 'what' => $i];
                                $steps[] = ['action' => 'wait', 'what' => $i];
                            }

                            return $steps;
                        },
                        choose(1, 100)
                    ),
                    function ($steps) {
                        $allSteps = count($steps);
                        $waitSteps = $allSteps / 2;

                        return tuple(
                            constant($steps),
                            vector($waitSteps, choose(0, $allSteps))
                        );
                    }
                )
            )
            ->then(function ($batchSize, $stepsAndWaitsForwardMovements) use ($originalClient) {
                $client = new BatchingHttpClient($originalClient, $batchSize);
                list($steps, $waitsForwardMovements) = $stepsAndWaitsForwardMovements;
                $steps = $this->alterStepsByDelayingWaits($steps, $waitsForwardMovements);
                $promises = [];
                foreach ($steps as $step) {
                    switch ($step['action']) {
                        case 'send':
                            $request = new Request('GET', '/'.$step['what']);
                            $promises[$step['what']] = $client->send($request);
                            break;
                        case 'wait':
                            $this->assertSame(
                                '/'.$step['what'],
                                (string) $promises[$step['what']]->wait()->getBody()
                            );
                            break;
                        default:
                            $this->fail('Step not supported: '.var_export($step, true));
                    }
                }
            });
    }

    private function alterStepsByDelayingWaits($steps, $waitsForwardMovements)
    {
        foreach ($waitsForwardMovements as $what => $delta) {
            $currentIndex = array_search($step = ['action' => 'wait', 'what' => $what], $steps);
            $this->assertNotFalse($currentIndex, 'Cannot find step: '.var_export($step, true));
            $newIndex = min(count($steps), $currentIndex + $delta);
            array_splice($steps, $newIndex, 0, [$steps[$currentIndex]]);
            array_splice($steps, $currentIndex, 1);
        }
        $steps = array_values($steps);

        return $steps;
    }
}
