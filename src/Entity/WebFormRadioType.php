<?php

namespace App\Entity;

use App\Repository\WebFormRadioTypeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=WebFormRadioTypeRepository::class)
 */
class WebFormRadioType extends WebFormElement
{
    /**
     * @ORM\Column(type="json")
     */
    private $choices;

    public function getType()
    {
        return "radio";
    }

    /**
     * @return mixed
     */
    public function getChoices()
    {
        return $this->choices;
    }

    /**
     * @param mixed $choices
     */
    public function setChoices($choices): void
    {
        $this->choices = $choices;
    }
}
