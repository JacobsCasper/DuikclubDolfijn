<?php

namespace App\Entity;

use App\Repository\UserSubscriptionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserSubscriptionRepository::class)
 */
class UserSubscription
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $subscriptionDate;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\CalenderItem", inversedBy="UserSubscription")
     * @ORM\JoinColumn(name="CalenderItem_id", referencedColumnName="id")
     */
    private $CalenderItem;

    /**
     * @return mixed
     */
    public function getSubscriptionDate()
    {
        return $this->subscriptionDate;
    }

    /**
     * @param mixed $subscriptionDate
     */
    public function setSubscriptionDate($subscriptionDate): void
    {
        $this->subscriptionDate = $subscriptionDate;
    }

    /**
     * @return mixed
     */
    public function getCalenderItem()
    {
        return $this->CalenderItem;
    }

    /**
     * @param mixed $CalenderItem
     */
    public function setCalenderItem($CalenderItem): void
    {
        $this->CalenderItem = $CalenderItem;
    }



    public function getId(): ?int
    {
        return $this->id;
    }
}
