<?php

namespace App\Entity;

use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;


abstract class AbstractEntity
{
    protected const STATUS_DEFAULT = 'default';

    #[Gedmo\Timestampable(on: 'create')]
    #[ORM\Column(name: 'created', type: Types::DATETIME_MUTABLE)]
    protected DateTime $created;

    #[ORM\Column(name: 'updated', type: Types::DATETIME_MUTABLE)]
    #[Gedmo\Timestampable]
    protected DateTime $updated;
    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['order:read'])]
    protected string $status = self::STATUS_DEFAULT;

    public function setStatus(string $status): self
    {
        $this->status = $status;
        return $this;
    }
}