<?php

namespace AbstractBabel\TranslateClient\Serializer;

use AbstractBabel\TranslateClient\Model\Translation;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

final class TranslationDenormalizer implements DenormalizerInterface, DenormalizerAwareInterface
{
    use DenormalizerAwareTrait;

    public function denormalize($data, $class, $format = null, array $context = []) : Translation
    {
        return new Translation($data['TranslatedText'], $data['TargetLanguageCode']);
    }

    public function supportsDenormalization($data, $type, $format = null) : bool
    {
        return Translation::class === $type;
    }
}
