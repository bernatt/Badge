<?php

namespace vending_machineBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use vending_machineBundle\Entity\baseDatetime;


/**
 * Transactions
 *
 * @ORM\Table(name="transactions")
 * @ORM\Entity(repositoryClass="vending_machineBundle\Repository\TransactionsRepository")
 */
class Transactions extends baseDatetime
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     * @var int
     *
     * @ORM\Column(name="quantity", type="integer")
     */
    private $quantity;

    /**
     * @var string
     *
     * @ORM\Column(name="price", type="decimal", precision=5, scale=2)
     */
    private $price;

    /**
     * @var string
     *
     * @ORM\Column(name="money_left", type="decimal", precision=7, scale=2)
     */
    private $moneyLeft;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="transactions")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="machine", inversedBy="transactions")
     * @ORM\JoinColumn(name="product_machine_id", referencedColumnName="product_id")
     */
    private $machine;




    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set productName
     *
     * @param string $productName
     *
     * @return Transactions
     */
    public function setProductName($productName)
    {
        $this->productName = $productName;

        return $this;
    }

    /**
     * Get productName
     *
     * @return string
     */
    public function getProductName()
    {
        return $this->productName;
    }

    /**
     * Set quantity
     *
     * @param integer $quantity
     *
     * @return Transactions
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set price
     *
     * @param string $price
     *
     * @return Transactions
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set moneyLeft
     *
     * @param string $moneyLeft
     *
     * @return Transactions
     */
    public function setMoneyLeft($moneyLeft)
    {
        $this->moneyLeft = $moneyLeft;

        return $this;
    }

    /**
     * Get moneyLeft
     *
     * @return string
     */
    public function getMoneyLeft()
    {
        return $this->moneyLeft;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Transactions
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     *
     * @return Transactions
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set user
     *
     * @param \vending_machineBundle\Entity\User $user
     *
     * @return Transactions
     */
    public function setUser(\vending_machineBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \vending_machineBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set machine
     *
     * @param \vending_machineBundle\Entity\machine $machine
     *
     * @return Transactions
     */
    public function setMachine(\vending_machineBundle\Entity\machine $machine = null)
    {
        $this->machine = $machine;

        return $this;
    }

    /**
     * Get machine
     *
     * @return \vending_machineBundle\Entity\machine
     */
    public function getMachine()
    {
        return $this->machine;
    }
}
