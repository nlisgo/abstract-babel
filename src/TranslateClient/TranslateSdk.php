<?php

namespace AbstractBabel\TranslateClient;

use AbstractBabel\TranslateClient\Client\Translate;
use AbstractBabel\TranslateClient\Serializer\TranslationDenormalizer;
use Aws\Translate\TranslateClient;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Serializer;

final class TranslateSdk
{
    private $translate;

    public function __construct(TranslateClient $client)
    {
        $serializer = new Serializer([
            new TranslationDenormalizer(),
        ], [new JsonEncoder()]);
        $this->translate = new Translate($client, $serializer);
    }

    public function translate() : Translate
    {
        return $this->translate;
    }
}
