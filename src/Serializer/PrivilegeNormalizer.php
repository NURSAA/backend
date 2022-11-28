<?php

namespace App\Serializer;

use ApiPlatform\Core\Api\IriConverterInterface;
use App\Entity\Ownership;
use App\Entity\RolePrivilege;
use Doctrine\ORM\EntityManagerInterface;
use ReflectionClass;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;

class PrivilegeNormalizer extends AbstractNormalizer implements ContextAwareNormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    protected string $alreadyCalled  = 'PRIVILEGE_ATTRIBUTE_NORMALIZER_ALREADY_CALLED';

    public function __construct(
        private Security $security,
        private EntityManagerInterface $em,
        private IriConverterInterface $iriConverter
    ) {
    }

    public function normalize(mixed $object, string $format = null, array $context = [])
    {
        $context[sprintf('%s_%s', $this->alreadyCalled, $context['resource_class'])] = true;

        $user = $this->security->getUser();

        $ownership = $this->em->getRepository(Ownership::class)->findOneBy([
            'user' => $user,
            'iri' => $this->iriConverter->getIriFromItem($object)
        ]);

        if ($ownership instanceof Ownership) {
            $reflectionClass = new ReflectionClass($object);
            $entityName = $reflectionClass->getShortName();
            $context['groups'] = array_map(function($group) use ($entityName) {
                return sprintf('%s:%s', $entityName, $group->getName());
            }, $reflectionClass->getProperties());

            return $this->normalizer->normalize($object, $format, $context);
        }

        $context['groups'] = [];
        $entityName = (new ReflectionClass($object))->getShortName();
        $rolePrivilege = $this->em->getRepository(RolePrivilege::class)->findOneBy([
            'entity' => $entityName,
            'role' => $user->getRoles()[0]
        ]);

        if (null === $groups = $rolePrivilege?->getGroups()) {
            return null;
        }

        $context['groups'] = array_map(function($group) use ($entityName) {
            return sprintf('%s:%s', $entityName, $group);
        }, $groups);

        return $this->normalizer->normalize($object, $format, $context);
    }
}