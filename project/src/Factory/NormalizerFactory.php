<?php

namespace App\Factory;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class NormalizerFactory
{
    private $normalizers;

    public function __construct(iterable $normalizers)
    {
        $this->normalizers = $normalizers;
    }

    public function getNormalizer($data)
    {
        foreach ($this->normalizers as $normalizer) {
            if ($normalizer instanceof NormalizerInterface && $normalizer->supportsNormalization($data)) {

                return $normalizer;
            }
        }

        return null;
    }
}