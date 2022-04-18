<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;


abstract class AbstractEntity
{
    #[Gedmo\Timestampable(on: "create")]
    #[ORM\Column(type: "datetime")]
    protected DateTime $created;

    #[Gedmo\Timestampable(on: "update")]
    #[ORM\Column(type: "datetime")]
    protected DateTime $updated;
}