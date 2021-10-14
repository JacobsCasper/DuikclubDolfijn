<?php

namespace App\Entity;

use App\Repository\SubscriptionRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * @ORM\Entity(repositoryClass=SubscriptionRepository::class)
 */
class Subscription
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $date;

    /**
     * Many Subscription have One User.
     * @ManyToOne(targetEntity="User", inversedBy="subscriptions")
     * @JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * Many Subscription have One CalenderItem.
     * @ManyToOne(targetEntity="CalenderItem", inversedBy="subscriptions")
     * @JoinColumn(name="CalenderItem_id", referencedColumnName="id")
     */
    private $calenderItem;

    /**
     * @ORM\Column(type="string", columnDefinition="ENUM('subscribed', 'awaiting')")
     */
    private $status;

    public function __construct(User $user, CalenderItem $calenderItem)
    {
        $this->setDate(new \DateTime());
        $this->setUser($user);
        $this->setCalenderItem($calenderItem);
        $this->setStatus('awaiting');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @return mixed
     */
    public function getDateString()
    {
        return $this->getDate()->format('d/m/Y');
    }

    /**
     * @param mixed $date
     */
    public function setDate($date): void
    {
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user): void
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getCalenderItem()
    {
        return $this->calenderItem;
    }

    /**
     * @param mixed $calenderItem
     */
    public function setCalenderItem($calenderItem): void
    {
        $this->calenderItem = $calenderItem;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status): void
    {
        $this->status = $status;
    }


    public function promote() : void
    {
        if(!$this->getCalenderItem()->maxSubscriptionsReached()) {
            $this->setStatus('subscribed');
        }
    }

    public function demote() : void
    {
        $this->setStatus('awaiting');
    }
}
