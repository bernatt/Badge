<?php

namespace vending_machineBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * machine
 *
 * @ORM\Table(name="machine")
 * @ORM\Entity(repositoryClass="vending_machineBundle\Repository\machineRepository")
 */
class machine
{
    /**
     * @var int
     *
     * @ORM\Column(name="product_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $product_id;

    /**
     * @var string
     *
     * @ORM\Column(name="product_name", type="string", unique=true)
     * @Assert\Length(min="3", minMessage="Za krótka nazwa")
     */
    private $productName;

    /**
     * @var float
     *@Assert\Type(
     *     type="double",
     *     message="Podana wartość {{ value }} nie jest poprawnym typen danych {{ type }}."
     * )
     *
     * @ORM\Column(name="price", type="decimal", precision=5, scale=2)
     */
    private $price;

    /**
     * @var int
     *
     * @ORM\Column(name="stock", type="integer", nullable=true)
     * @Assert\Type(
     *     type="integer",
     *     message="Podana wartość {{ value }} nie jest poprawnym typen danych {{ type }}."
     * )
     */
    private $stock;

    /**
     * @var int
     *
     * @ORM\Column(name="numberOfSold", type="integer")
     */
    private $numberOfSold = 0;


    /**
     * @ORM\OneToMany(targetEntity="Transactions", mappedBy="machine")
     */
    private $transactions;



    public function __toString()
    {
        return $this->productName;

    }


    public function __construct()
    {
        $this->transactions = new ArrayCollection();
    }


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->product_id;
    }

    /**
     * Set productName
     *
     * @param string $productName
     *
     * @return machine
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
     * Set price
     *
     * @param string $price
     *
     * @return machine
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
     * Set stock
     *
     * @param integer $stock
     *
     * @return machine
     */
    public function setStock($stock)
    {
        $this->stock = $stock;

        return $this;
    }

    /**
     * Get stock
     *
     * @return int
     */
    public function getStock()
    {
        return $this->stock;
    }

    public function stockCorrection($quantity)
    {
        $this->stock = $this->stock - $quantity;
        return $this;
    }

    /**
     * Get productId
     *
     * @return integer
     */
    public function getProductId()
    {
        return $this->product_id;
    }

    /**
     * Add transaction
     *
     * @param \vending_machineBundle\Entity\Transactions $transaction
     *
     * @return machine
     */
    public function addTransaction(\vending_machineBundle\Entity\Transactions $transaction)
    {
        $this->transactions[] = $transaction;

        return $this;
    }

    /**
     * Remove transaction
     *
     * @param \vending_machineBundle\Entity\Transactions $transaction
     */
    public function removeTransaction(\vending_machineBundle\Entity\Transactions $transaction)
    {
        $this->transactions->removeElement($transaction);
    }

    /**
     * Get transactions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTransactions()
    {
        return $this->transactions;
    }

    /**
     * Set numberOfSold
     *
     * @param integer $numberOfSold
     *
     * @return machine
     */
    public function setNumberOfSold($numberOfSold)
    {
        $this->numberOfSold = $numberOfSold;

        return $this;
    }

    /**
     * Get numberOfSold
     *
     * @return integer
     */
    public function getNumberOfSold()
    {
        return $this->numberOfSold;
    }

    public function updateNumberOfSold($quantity)
    {
        $this->numberOfSold = $this->numberOfSold + $quantity;
        return $this;
    }
}
