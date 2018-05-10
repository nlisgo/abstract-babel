<?php

namespace tests\AbstractBabel\CrossRefClient\Client;

use AbstractBabel\CrossRefClient\ApiClient\WorksClient;
use AbstractBabel\CrossRefClient\Client\Works;
use AbstractBabel\CrossRefClient\HttpClient\HttpClient;
use AbstractBabel\CrossRefClient\Model\Work as ModelWork;
use AbstractBabel\CrossRefClient\Result\ArrayResult;
use GuzzleHttp\Promise\FulfilledPromise;
use GuzzleHttp\Psr7\Request;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use tests\AbstractBabel\CrossRefClient\RequestConstraint;

/**
 * @covers \AbstractBabel\CrossRefClient\Client\Token
 */
final class WorksTest extends TestCase
{
    private $doi;
    private $denormalizer;
    private $httpClient;
    /** @var Works */
    private $works;
    /** @var WorksClient */
    private $worksClient;

    /**
     * @before
     */
    public function prepareDependencies()
    {
        $this->doi = 'doi';
        $this->denormalizer = $this->getMockBuilder(DenormalizerInterface::class)
            ->setMethods(['denormalize', 'supportsDenormalization'])
            ->getMock();
        $this->httpClient = $this->getMockBuilder(HttpClient::class)
            ->setMethods(['send'])
            ->getMock();
        $this->worksClient = new WorksClient($this->httpClient);
        $this->works = new Works($this->worksClient, $this->denormalizer);
    }

    /**
     * @test
     */
    public function it_will_get_an_abstract()
    {
        $request = new Request(
            'GET',
            'works/doi',
            ['User-Agent' => 'CrossRefClient']
        );
        $response = new FulfilledPromise(new ArrayResult([
            'message' => [
                'abstract' => 'abstract',
            ],
        ]));
        $token = new ModelWork('abstract');
        $this->denormalizer
            ->method('denormalize')
            ->with($response->wait()->toArray(), ModelWork::class)
            ->willReturn($token);
        $this->httpClient
            ->expects($this->once())
            ->method('send')
            ->with(RequestConstraint::equalTo($request))
            ->willReturn($response);
        $this->assertSame($token, $this->works->get('doi')->wait());
    }
}
