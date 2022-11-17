<?php

namespace App\Serializer;

use ApiPlatform\Core\Api\IriConverterInterface;
use App\Entity\Privilege;
use App\Entity\PrivilegeGroup;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;

abstract class AbstractNormalizer implements ContextAwareNormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    protected string $alreadyCalled;

    public function __construct(
        private Security $security,
        private EntityManagerInterface $em,
        private IriConverterInterface $iriConverter
    ){
    }

    public function normalize(mixed $object, string $format = null, array $context = [])
    {
        $context['groups'] = [];
//        if (true) {
//            $exploded = explode("\\", $context['resource_class']);
//            $context['groups'][] = $exploded[count($exploded) - 1];
//        }

        $context[sprintf('%s_%s', $this->alreadyCalled, $context['resource_class'])] = true;

        $privilege = $this->em->getRepository(Privilege::class)->findOneBy([
            'user' => $this->security->getUser(),
            'iri' => $this->iriConverter->getIriFromItem($object)
        ]);

        if (!$privilege instanceof Privilege) {
            return $this->normalizer->normalize(null, $format, ['skip_null_values' => true]);
        }

        if (null !== $groups = $privilege->getPrivilegeGroups()) {
            $context['groups'][] = $groups->map(function($group) {
                return $group->getName();
            });
        }

        return $this->normalizer->normalize($object, $format, $context);
    }

    public function supportsNormalization($data, $format = null, array $context = []): bool
    {
        return false;
    }
}