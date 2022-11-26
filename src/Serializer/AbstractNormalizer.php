<?php

namespace App\Serializer;

use ApiPlatform\Core\Api\IriConverterInterface;
use App\Entity\Ownership;
use App\Entity\RolePrivilege;
use App\EventSubscriber\PrivilegeSubscriber;
use Doctrine\ORM\EntityManagerInterface;
use ReflectionClass;
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
    ) {
    }

    public function normalize(mixed $object, string $format = null, array $context = [])
    {
        $context['groups'] = [];
        $context[sprintf('%s_%s', $this->alreadyCalled, $context['resource_class'])] = true;

        $user = $this->security->getUser();

        $ownership = $this->em->getRepository(Ownership::class)->findOneBy([
            'user' => $user,
            'iri' => $this->iriConverter->getIriFromItem($object)
        ]);

        if ($ownership instanceof Ownership) {
            return $this->normalizer->normalize($object, $format, $context);
        }

        $entityName = (new ReflectionClass($object))->getShortName();
        $rolePrivilege = $this->em->getRepository(RolePrivilege::class)->findOneBy([
            'entity' => $entityName,
            'role' => $user->getRoles()[0]
        ]);

        $context['groups'] = [];
        if (null !== $groups = $rolePrivilege?->getGroups()) {
            $context['groups'] = array_map(function($group) use ($entityName) {
                return sprintf('%s:%s', $entityName, $group);
            }, $groups);
        }

        return $this->normalizer->normalize($object, $format, $context);
    }

    public function supportsNormalization($data, $format = null, array $context = []): bool
    {
        return false;
    }
}