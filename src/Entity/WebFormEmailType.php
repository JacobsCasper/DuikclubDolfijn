<?php

namespace App\Entity;

use App\Repository\WebFormEmailTypeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=WebFormEmailTypeRepository::class)
 */
class WebFormEmailType extends WebFormElement
{

    public function getType()
    {
        return "email";
    }
}
