<?php

namespace App\Entity;

use App\Repository\WebFormRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;

/**
 * @ORM\Entity(repositoryClass=WebFormRepository::class)
 */
class WebForm
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @OneToMany(targetEntity="App\Entity\WebFormElement", mappedBy="WebForm")
     */
    private $form_elements;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $buttonName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $buttonType;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getButtonName()
    {
        return $this->buttonName;
    }

    /**
     * @param mixed $buttonName
     */
    public function setButtonName($buttonName): void
    {
        $this->buttonName = $buttonName;
    }

    /**
     * @return mixed
     */
    public function getButtonType()
    {
        return $this->buttonType;
    }

    /**
     * @param mixed $buttonType
     */
    public function setButtonType($buttonType): void
    {
        $this->buttonType = $buttonType;
    }

    /**
     * @return ArrayCollection
     */
    public function getFormElements(): ArrayCollection
    {
        return $this->form_elements;
    }

    /**
     * @param ArrayCollection $form_elements
     */
    public function setFormElements(ArrayCollection $form_elements): void
    {
        $this->form_elements = $form_elements;
    }


}
