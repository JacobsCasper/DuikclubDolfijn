<?php

namespace App\Entity;

use App\Repository\WebFormStringTypeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=WebFormStringTypeRepository::class)
 */
class WebFormStringType extends WebFormElement
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="smallint")
     */
    private $multiline;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMultiline(): ?int
    {
        return $this->multiline;
    }

    public function setMultiline(int $multiline): self
    {
        $this->multiline = $multiline;

        return $this;
    }

    public function getType()
    {
        return "string";
    }
}
