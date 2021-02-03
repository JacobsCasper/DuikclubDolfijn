<?php

namespace App\Entity;

use App\Repository\WebFormIntTypeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=WebFormIntTypeRepository::class)
 */
class WebFormIntType extends WebFormElement
{

    public function getType()
    {
        return "int";
    }
}
