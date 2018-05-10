<?php

namespace tests\AbstractBabel\CrossRefClient\Model;

use AbstractBabel\CrossRefClient\Model\Work;
use PHPUnit\Framework\TestCase;

/**
 * @covers \AbstractBabel\CrossRefClient\Model\Work
 */
final class WorkTest extends TestCase
{
    /** @var Work */
    private $work;

    /**
     * @before
     */
    public function prepare_token()
    {
        $this->work = new Work('abstract');
    }

    /**
     * @test
     */
    public function it_has_an_abstract()
    {
        $this->assertSame('abstract', $this->work->getAbstract());
    }
}
