<?php

namespace App\Entity;

use App\Repository\UserRepository;
use App\Services\FileService;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\OneToMany;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface, \Serializable
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
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $phoneNumber;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @ORM\Column(type="boolean")
     */
    private $isBestuursLid;

    /**
     * @ORM\Column(type="text", length=255)
     */
    private $profilePicturePath;

    /**
     * One user has many subscriptions. This is the inverse side.
     * @OneToMany(targetEntity="Subscription", mappedBy="user")
     */
    private Collection $subscriptions;


    public function __construct()
    {
        $this->subscriptions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * @param mixed $phoneNumber
     */
    public function setPhoneNumber($phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param mixed $address
     */
    public function setAddress($address): void
    {
        $this->address = $address;
    }



    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description): void
    {
        $this->description = $description;
    }



    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getRoles()
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @return mixed
     */
    public function getProfilePicturePath()
    {
        return $this->profilePicturePath;
    }

    /**
     * @param mixed $profilePicturePath
     */
    public function setProfilePicturePath($profilePicturePath): void
    {
        $this->profilePicturePath = $profilePicturePath;
    }

    /**
     * @return mixed
     */
    public function getIsBestuursLid()
    {
        return $this->isBestuursLid;
    }

    /**
     * @param mixed $isBestuursLid
     */
    public function setIsBestuursLid($isBestuursLid): void
    {
        $this->isBestuursLid = $isBestuursLid;
    }





    public function makeUser()
    {
        $this->roles = array();
        array_push($this->roles, "ROLE_USER");
    }

    public function makeInstructor()
    {
        $this->makeUser();
        array_push($this->roles, "ROLE_INST");
    }

    public function makeAdmin()
    {
        $this->makeUser();
        array_push($this->roles, "ROLE_INST");
        array_push($this->roles, "ROLE_ADMIN");
    }

    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function serialize()
    {
        return serialize([
            $this->id,
            $this->username,
            $this->email,
            $this->password
        ]);
    }

    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->username,
            $this->email,
            $this->password
        ) = unserialize($serialized, ['allowed_classes' => false]);
    }

    public function equals(User $user): bool
    {
        if($user->getEmail() == $this->getEmail() && $user->getId() == $this->getId()){
            return true;
        }
        return false;
    }

    public function setPicture(SluggerInterface $slugger, $picture, string $uploadDir)
    {
        $fileService = new FileService();
        $newFilename = $fileService->uploadFile($picture, $slugger, $uploadDir);
        $this->setProfilePicturePath($newFilename);
    }

    public function removePicture(string $uploadDir)
    {
        unlink($uploadDir . "/" . $this->getProfilePicturePath());
        $this->setProfilePicturePath("");
    }

    /**
     * @return Collection
     */
    public function getSubscriptions(): Collection
    {
        return $this->subscriptions;
    }

    /**
     * @param Collection $subscriptions
     */
    public function setSubscriptions(Collection $subscriptions): void
    {
        $this->subscriptions = new ArrayCollection($subscriptions->getValues());
    }


}
