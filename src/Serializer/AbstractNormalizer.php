<?php

namespace App\Serializer;

use App\Entity\Reservation;
use App\Entity\Restaurant;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;

abstract class AbstractNormalizer
{
    protected string $alreadyCalled = 'PRIVILEGE_ATTRIBUTE_NORMALIZER_ALREADY_CALLED';

    protected const ALLOWED_CLASSES = [
        Reservation::class,
        Restaurant::class
    ];

    public function supportsNormalization($data, $format = null, array $context = []): bool
    {
        try {
            if (isset($context[sprintf('%s_%s', $this->alreadyCalled, get_class($data))])) {
                return false;
            }

            return in_array(get_class($data), self::ALLOWED_CLASSES);
        } catch (\Throwable $throwable) {
        }

        return false;
    }
}
