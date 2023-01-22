<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use ApiPlatform\Core\DataPersister\ResumableDataPersisterInterface;
use Symfony\Component\Mailer\MailerInterface;

class EntityPersister implements ContextAwareDataPersisterInterface,
    ResumableDataPersisterInterface
{

    public function __construct(
        private ContextAwareDataPersisterInterface $decorated,
    ) {
    }

    public function supports($data, array $context = []): bool
    {
        return true;
    }

    public function persist($data, array $context = []): void
    {
        $this->decorated->persist($data, $context);
    }

    public function remove($data, array $context = []): void
    {
        $this->decorated->remove($data, $context);
    }

    public function resumable(array $context = []): bool
    {
        return true;
    }
}