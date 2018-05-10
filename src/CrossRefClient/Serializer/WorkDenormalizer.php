<?php

namespace AbstractBabel\CrossRefClient\Serializer;

use AbstractBabel\CrossRefClient\Model\Work;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

final class WorkDenormalizer implements DenormalizerInterface, DenormalizerAwareInterface
{
    use DenormalizerAwareTrait;

    public function denormalize($data, $class, $format = null, array $context = []) : Work
    {
        return new Work($data['message']['abstract']);
    }

    public function supportsDenormalization($data, $type, $format = null) : bool
    {
        return Work::class === $type;
    }
}
