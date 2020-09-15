<?php

namespace App\Entity;

use App\Repository\PageRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\OneToOne;

/**
 * @ORM\Entity(repositoryClass=PageRepository::class)
 */
class Page
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
     * @ORM\Column(type="string", length=255)
     */
    private $Author;

    /**
     * @ORM\Column(type="string", length=6000, nullable=true)
     */
    private $body;

    /**
     * @ORM\Column(type="boolean")
     */
    private $published;

    /**
     * @ORM\Column(type="boolean")
     */
    private $homepageEnabled;

    /**
     * @ORM\Column(type="boolean")
     */
    private $navigationEnabled;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $navigationText;

    /**
     * @ORM\Column(type="datetime")
     */
    private $submitDate;

    /**
     * @ORM\Column(type="text", length=255)
     */
    private $picturePath;

/*
 * deze 2 staan in de database opgeslagen
    private $formId;
    private $fileLocation;
*/

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getSubmitDate()
    {
        return $this->submitDate->format('d/m/Y H:i');
    }

    /**
     * @param mixed $submitDate
     */
    public function setSubmitDate($submitDate): void
    {
        $this->submitDate = $submitDate;
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
    public function getAuthor()
    {
        return $this->Author;
    }

    /**
     * @param mixed $Author
     */
    public function setAuthor($Author): void
    {
        $this->Author = $Author;
    }



    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(?string $body): self
    {
        $this->body = $body;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * @param mixed $published
     */
    public function setPublished($published): void
    {
        $this->published = $published;
    }

    /**
     * @return mixed
     */
    public function getHomepageEnabled()
    {
        return $this->homepageEnabled;
    }

    /**
     * @param mixed $homepageEnabled
     */
    public function setHomepageEnabled($homepageEnabled): void
    {
        $this->homepageEnabled = $homepageEnabled;
    }

    /**
     * @return mixed
     */
    public function getNavigationEnabled()
    {
        return $this->navigationEnabled;
    }

    /**
     * @param mixed $navigationEnabled
     */
    public function setNavigationEnabled($navigationEnabled): void
    {
        $this->navigationEnabled = $navigationEnabled;
    }



    public function getNavigationText(): ?string
    {
        return $this->navigationText;
    }

    public function setNavigationText(?string $navigationText): self
    {
        $this->navigationText = $navigationText;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPicturePath()
    {
        return $this->picturePath;
    }

    /**
     * @param mixed $picturePath
     */
    public function setPicturePath($picturePath): void
    {
        $this->picturePath = $picturePath;
    }



}
