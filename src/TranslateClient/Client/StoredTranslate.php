<?php

namespace AbstractBabel\TranslateClient\Client;

use AbstractBabel\TranslateClient\Model\Translation;
use Aws\Translate\TranslateClient;
use Exception;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

final class StoredTranslate
{
    private $storedDir;

    public function __construct(string $storedDir = null)
    {
        $this->storedDir = is_string($storedDir) ? rtrim($storedDir, '/').'/' : $storedDir;
    }

    public function get(
        string $doi,
        string $to
    ) : Translation {
        if ($this->storedDir) {
            $file = sprintf('%s/%s/%s.json', $this->storedDir, $doi, $to);
            if (is_file($file)) {
                $store = json_decode(file_get_contents($file), true);
                return new Translation($store['original'], $store['abstract'], $to);
            }
        }

        throw new Exception('Store not found!');
    }
}
