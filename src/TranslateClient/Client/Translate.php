<?php

namespace AbstractBabel\TranslateClient\Client;

use AbstractBabel\TranslateClient\Model\Translation;
use Aws\Translate\TranslateClient;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

final class Translate
{
    private $client;
    private $serializer;

    public function __construct(TranslateClient $client, DenormalizerInterface $serializer)
    {
        $this->client = $client;
        $this->serializer = $serializer;
    }

    public function get(
        string $text,
        string $to,
        string $from = 'en'
    ) : Translation {
        $message = $this->client->translateText([
            'SourceLanguageCode' => $from,
            'TargetLanguageCode' => $to,
            'Text' => $text,
        ]);

        return $this->serializer->denormalize($message->toArray() + ['original' => $text], Translation::class);
    }
}
