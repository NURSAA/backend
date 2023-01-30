<?php

namespace App\Entity;

use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;


abstract class AbstractEntity
{
    #[Gedmo\Timestampable(on: 'create')]
    #[ORM\Column(name: 'created', type: Types::DATETIME_MUTABLE)]
    protected DateTime $created;

    #[ORM\Column(name: 'updated', type: Types::DATETIME_MUTABLE)]
    #[Gedmo\Timestampable]
    protected DateTime $updated;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['order:read'])]
    protected bool $softDelete = false;

    public function setDeletedStatus(bool $softDelete): self
    {
        $this->softDelete = $softDelete;
        return $this;
    }
}