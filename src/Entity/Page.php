<?php

namespace App\Entity;

use App\Repository\PageRepository;
use App\Services\FileService;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\String\Slugger\SluggerInterface;

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

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $formId;

    /**
     * @ORM\Column(type="json")
     */
    private $filePaths;

    /**
     * @ORM\Column(type="json")
     */
    private $fileNames;

    public function __construct()
    {
        $this->setFilePaths([]);
        $this->setFileNames([]);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getFormId()
    {
        return $this->formId;
    }

    /**
     * @param mixed $formId
     */
    public function setFormId($formId): void
    {
        $this->formId = $formId;
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

    /**
     * @return mixed
     */
    public function getFilePaths()
    {
        return $this->filePaths;
    }

    /**
     * @param mixed $filePaths
     */
    public function setFilePaths($filePaths): void
    {
        $this->filePaths = $filePaths;
    }

    /**
     * @return mixed
     */
    public function getFileNames()
    {
        return $this->fileNames;
    }

    /**
     * @param mixed $fileNames
     */
    public function setFileNames($fileNames): void
    {
        $this->fileNames = $fileNames;
    }

    public function setPicture(SluggerInterface $slugger, $picture, string $uploadDir)
    {
        $fileService = new FileService();
        $newFilename = $fileService->uploadFile($picture, $slugger, $uploadDir);
        $this->setPicturePath($newFilename);
    }

    public function removePicture(string $uploadDir)
    {
        unlink($uploadDir . "/" . $this->getPicturePath());
        $this->setPicturePath("");
    }

    public function addFile(SluggerInterface $slugger, string $uploadDir, string $name, $file)
    {
        $fileService = new FileService();
        $newFilename = $fileService->uploadFile($file, $slugger, $uploadDir);

        $filepaths = $this->getFilePaths();
        $filenames = $this->getFileNames();

        $filepaths[]= $newFilename;
        $filenames[]= $name;

        $this->setFilePaths($filepaths);
        $this->setFileNames($filenames);
    }

    public function removeFile(string $name, string $uploadDir)
    {
        $path = "";
        foreach ($this->getFileNames() as $key => $n){
            if($n == $name){
                $path = $this->getFilePaths()[$key];
            }
        }
        if($path != ""){
            unlink($uploadDir . "/" . $path);
            $filtered_collection = array_filter($this->getFilePaths(), function ($item) use ($path) {
                if(strcmp (  $item ,  $path ) != 0){
                    return $item;
                }
            });

            $this->setFilePaths($filtered_collection);

            $filtered_collection = array_filter($this->getFileNames(), function ($item) use ($name) {
                if(strcmp (  $item ,  $name ) != 0){
                    return $item;
                }
            });

            $this->setFileNames($filtered_collection);
        }


    }

    public function destruct(string $uploadDir)
    {
        $this->removePicture($uploadDir);
        foreach ($this->getFilePaths() as $path){
            unlink($uploadDir . "/" . $path);
        }
    }

}
