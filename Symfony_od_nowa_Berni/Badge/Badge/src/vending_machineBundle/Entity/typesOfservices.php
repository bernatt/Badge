<?php

namespace vending_machineBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * typesOfservices
 *
 * @ORM\Table(name="types_ofservices")
 * @ORM\Entity(repositoryClass="vending_machineBundle\Repository\typesOfservicesRepository")
 */
class typesOfservices
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="cash", type="decimal", precision=10, scale=2)
     */
    private $cash;

    /**
     * @var string
     *
     * @ORM\Column(name="total_cash", type="decimal", precision=10, scale=2)
     */
    private $totalCash;

    /**
     * @ORM\OneToMany(targetEntity="machine", mappedBy="types")
     */
    private $machine;

    public function __construct()
    {
        $this->reviews = new ArrayCollection();
    }


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
     * Set name
     *
     * @param string $name
     *
     * @return typesOfservices
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set cash
     *
     * @param string $cash
     *
     * @return typesOfservices
     */
    public function setCash($cash)
    {
        $this->cash = $cash;

        return $this;
    }

    /**
     * Get cash
     *
     * @return string
     */
    public function getCash()
    {
        return $this->cash;
    }

    /**
     * Set totalCash
     *
     * @param string $totalCash
     *
     * @return typesOfservices
     */
    public function setTotalCash($totalCash)
    {
        $this->totalCash = $totalCash;

        return $this;
    }

    /**
     * Get totalCash
     *
     * @return string
     */
    public function getTotalCash()
    {
        return $this->totalCash;
    }
}

