<?php

namespace App\Entity;

use App\Repository\CalenderItemRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\OneToMany;
use mysql_xdevapi\Exception;
use function Symfony\Component\String\u;

/**
 * @ORM\Entity(repositoryClass=CalenderItemRepository::class)
 */
class CalenderItem
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $title;

    /**
     * @ORM\Column(type="integer")
     */
    private $CalenderType;

    /**
     * @ORM\Column(type="text", length=100)
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     */
    private $endDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $startDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $submitDate;

    /**
     * @ORM\Column(type="text", length=100)
     */
    private $author;

    /**
     * @ORM\Column(type="text")
     */
    private $details;

    /**
     * Many CalenderItems have Many Users.
     * @ManyToMany(targetEntity="User", inversedBy="CalenderItems")
     * @JoinTable(name="users_calenderItems")
     */
    private $users;

    /**
     * @ORM\Column (type="integer")
     */
    private $maxSubscriptions;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $subscriptionEndDate;

    public function __construct() {
        $this->users = new ArrayCollection();
    }

    /**
     * @return Collection
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    /**
     * @param Collection $users
     */
    public function setUsers(Collection $users): void
    {
        $this->users = $users;
    }

    /**
     * @return mixed
     */
    public function getMaxSubscriptions()
    {
        return $this->maxSubscriptions;
    }

    /**
     * @param mixed $maxSubscriptions
     */
    public function setMaxSubscriptions($maxSubscriptions): void
    {
        $this->maxSubscriptions = $maxSubscriptions;
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title): void
    {
        $this->title = $title;
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

    /**
     * @return mixed
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * @param mixed $endDate
     */
    public function setEndDate(\DateTime $endDate): void
    {
        $this->endDate = $endDate;
    }

    /**
     * @return mixed
     */
    public function getSubmitDate()
    {
        return $this->submitDate->format('d/m/Y');
    }

    /**
     * @param mixed $submitDate
     */
    public function setSubmitDate(\DateTime  $submitDate): void
    {
        $this->submitDate = $submitDate;
    }


    /**
     * @return mixed
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param mixed $author
     */
    public function setAuthor($author): void
    {
        $this->author = $author;
    }

    /**
     * @return mixed
     */
    public function getDetails()
    {
        return $this->details;
    }

    /**
     * @param mixed $details
     */
    public function setDetails($details): void
    {
        $this->details = $details;
    }

    /**
     * @return mixed
     */
    public function getCalenderType()
    {
        return $this->CalenderType;
    }

    /**
     * @param mixed $CalenderType
     */
    public function setCalenderType($CalenderType): void
    {
        $this->CalenderType = $CalenderType;
    }

    /**
     * @return mixed
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @param mixed $startDate
     */
    public function setStartDate(\DateTime $startDate): void
    {
        $this->startDate = $startDate;
    }

    /**
     * @return mixed
     */
    public function getSubscriptionEndDate()
    {
        return $this->subscriptionEndDate;
    }

    /**
     * @param mixed $subscriptionEndDate
     */
    public function setSubscriptionEndDate(\DateTime $subscriptionEndDate): void
    {
        if($subscriptionEndDate == ''){
            $this->subscriptionEndDate = null;
        } else {
            $this->subscriptionEndDate = $subscriptionEndDate;
        }
    }



    public function maxSubscriptionsReached(){
        if(count($this->getUsers()) >= $this->maxSubscriptions){
            return true;
        }
        return false;
    }

    public function subscriptionDateExpired(){
        $today = new DateTime();

        if($today >= $this->endDate){
            return true; //expired
        } else {
            if($this->subscriptionEndDate == null){
                return false;
            }
            if($today >= $this->subscriptionEndDate){
                return true; //expired
            } else {
                return false;
            }
        }
    }

    public function addSubscriber(User $user): bool
    {
        if($this->maxSubscriptionsReached() || $this->subscriptionDateExpired()){
            return false;
        } else {
            $this->getUsers()->add($user);
            return true;
        }
    }

    public function removeSubscriber(User $user): void
    {
        $this->getUsers()->remove($user->getId());
    }

}
