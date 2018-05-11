<?php

namespace AbstractBabel\TranslateClient;

use AbstractBabel\TranslateClient\Client\StoredTranslate;
use AbstractBabel\TranslateClient\Client\Translate;
use AbstractBabel\TranslateClient\Serializer\TranslationDenormalizer;
use Aws\Translate\TranslateClient;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Serializer;

final class TranslateSdk
{
    private $stored;
    private $translate;

    public function __construct(TranslateClient $client, StoredTranslate $stored)
    {

        $serializer = new Serializer([
            new TranslationDenormalizer(),
        ], [new JsonEncoder()]);
        $this->translate = new Translate($client, $serializer);
        $this->stored = $stored;
    }

    public function stored() : StoredTranslate
    {
        return $this->stored;
    }

    public function translate() : Translate
    {
        return $this->translate;
    }
}
