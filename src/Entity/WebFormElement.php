<?php

namespace App\Entity;

use App\Repository\WebFormElementRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=WebFormElementRepository::class)
 */
class WebFormElement
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\WebForm", inversedBy="WebFormElement")
     * @ORM\JoinColumn(name="Parent_id", referencedColumnName="id")
     */
    private $Parent_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $label;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $linked_entity;

    /**
     * @ORM\Column(type="boolean")
     */
    private $required;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getParentId()
    {
        return $this->Parent_id;
    }

    /**
     * @param mixed $Parent_id
     */
    public function setParentId($Parent_id): void
    {
        $this->Parent_id = $Parent_id;
    }

    /**
     * @return mixed
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param mixed $label
     */
    public function setLabel($label): void
    {
        $this->label = $label;
    }

    /**
     * @return mixed
     */
    public function getLinkedEntity()
    {
        return $this->linked_entity;
    }

    /**
     * @param mixed $linked_entity
     */
    public function setLinkedEntity($linked_entity): void
    {
        $this->linked_entity = $linked_entity;
    }

    /**
     * @return mixed
     */
    public function getRequired()
    {
        return $this->required;
    }

    /**
     * @param mixed $required
     */
    public function setRequired($required): void
    {
        $this->required = $required;
    }


}
