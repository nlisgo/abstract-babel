<?php

namespace tests\AbstractBabel\CrossRefClient\Serializer;

use AbstractBabel\CrossRefClient\Model\Work;
use AbstractBabel\CrossRefClient\Serializer\WorkDenormalizer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

/**
 * @covers \AbstractBabel\CrossRefClient\Serializer\TokenDenormalizer
 */
final class WorkDenormalizerTest extends TestCase
{
    /** @var WorkDenormalizer */
    private $normalizer;

    /**
     * @before
     */
    protected function setUpNormalizer()
    {
        $this->normalizer = new WorkDenormalizer();
    }

    /**
     * @test
     */
    public function it_is_a_denormalizer()
    {
        $this->assertInstanceOf(DenormalizerInterface::class, $this->normalizer);
    }

    /**
     * @test
     * @dataProvider canDenormalizeProvider
     */
    public function it_can_denormalize_works($data, $format, array $context, bool $expected)
    {
        $this->assertSame($expected, $this->normalizer->supportsDenormalization($data, $format, $context));
    }

    public function canDenormalizeProvider() : array
    {
        return [
            'token' => [[], Work::class, [], true],
            'non-token' => [[], get_class($this), [], false],
        ];
    }

    /**
     * @test
     * @dataProvider denormalizeProvider
     */
    public function it_will_denormalize_works(array $json, Work $expected)
    {
        $this->assertEquals($expected, $this->normalizer->denormalize($json, Work::class));
    }

    public function denormalizeProvider() : array
    {
        return [
            'complete' => [
                [
                    'status' => 'ok',
                    'message-type' => 'work',
                    'message-version' => '1.0.0',
                    'message' => [
                        'abstract' => 'abstract',
                    ],
                ],
                new Work('abstract'),
            ],
        ];
    }
}
