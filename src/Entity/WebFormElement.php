<?php

namespace App\Entity;

use App\Repository\WebFormElementRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=WebFormElementRepository::class)
 */
abstract class WebFormElement
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\WebForm", inversedBy="WebFormElement", cascade={"persist"})
     * @ORM\JoinColumn(name="Parent_id", referencedColumnName="id")
     */
    private $parent_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $label;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $frontLabel;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $endLabel;

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
        return $this->parent_id;
    }

    /**
     * @param mixed $parent_id
     */
    public function setParentId($parent_id): void
    {
        $this->parent_id = $parent_id;
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

    /**
     * @return mixed
     */
    public function getFrontLabel()
    {
        return $this->frontLabel;
    }

    /**
     * @param mixed $frontLabel
     */
    public function setFrontLabel($frontLabel): void
    {
        $this->frontLabel = $frontLabel;
    }

    /**
     * @return mixed
     */
    public function getEndLabel()
    {
        return $this->endLabel;
    }

    /**
     * @param mixed $endLabel
     */
    public function setEndLabel($endLabel): void
    {
        $this->endLabel = $endLabel;
    }



    public abstract function getType();

}
