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
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $title;

    /**
     * @OneToMany(targetEntity="WebFormElement", mappedBy="WebForm")
     */
    private $form_elements;

    /**
     * @OneToMany(targetEntity="Answer", mappedBy="WebForm")
     */
    private $answers;

    /**
     * @ORM\Column(type="boolean")
     */
    private $open;

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

    public function __construct(){
        $this->form_elements = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getFormElements()
    {
        return $this->form_elements;
    }

    /**
     * @param mixed $form_elements
     */
    public function setFormElements($form_elements): void
    {
        $this->form_elements = $form_elements;
    }

    /**
     * @return mixed
     */
    public function getOpen()
    {
        return $this->open;
    }

    /**
     * @param mixed $open
     */
    public function setOpen($open): void
    {
        $this->open = $open;
    }

    /**
     * @return mixed
     */
    public function getAnswers()
    {
        return $this->answers;
    }

    /**
     * @param mixed $answers
     */
    public function setAnswers($answers): void
    {
        $this->answers = $answers;
    }



    public function addFormElement($element){
        $this->getFormElements()->add($element);
    }

}
