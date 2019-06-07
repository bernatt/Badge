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
     * @var float
     *
     * @ORM\Column(name="cash", type="decimal", precision=10, scale=2)
     */
    private $cash;

    /**
     * @var float
     *
     * @ORM\Column(name="total_cash", type="decimal", precision=10, scale=2)
     */
    private $totalCash;

    /**
     * @ORM\OneToMany(targetEntity="KindOfMachines", mappedBy="type")
     */
    private $kindOfMachines;

    /**
     * @ORM\OneToMany(targetEntity="Canteen", mappedBy="type")
     */
    private $canteen;


    public function __construct()
    {
        $this->kindOfMachines = new ArrayCollection();
        $this->canteen = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
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

    /**
     * Add kindOfMachine
     *
     * @param \vending_machineBundle\Entity\KindOfMachines $kindOfMachine
     *
     * @return typesOfservices
     */
    public function addKindOfMachine(\vending_machineBundle\Entity\KindOfMachines $kindOfMachine)
    {
        $this->kindOfMachines[] = $kindOfMachine;

        return $this;
    }

    /**
     * Remove kindOfMachine
     *
     * @param \vending_machineBundle\Entity\KindOfMachines $kindOfMachine
     */
    public function removeKindOfMachine(\vending_machineBundle\Entity\KindOfMachines $kindOfMachine)
    {
        $this->kindOfMachines->removeElement($kindOfMachine);
    }

    /**
     * Get kindOfMachines
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getKindOfMachines()
    {
        return $this->kindOfMachines;
    }

    /**
     * Add canteen
     *
     * @param \vending_machineBundle\Entity\Canteen $canteen
     *
     * @return typesOfservices
     */
    public function addCanteen(\vending_machineBundle\Entity\Canteen $canteen)
    {
        $this->canteen[] = $canteen;

        return $this;
    }

    /**
     * Remove canteen
     *
     * @param \vending_machineBundle\Entity\Canteen $canteen
     */
    public function removeCanteen(\vending_machineBundle\Entity\Canteen $canteen)
    {
        $this->canteen->removeElement($canteen);
    }

    /**
     * Get canteen
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCanteen()
    {
        return $this->canteen;
    }

    public function cashFromService($amount)
    {
        $this->cash = $this->cash + $amount;
    }

    public function updateTotalCash($distributor, $canteen)
    {
        $this->totalCash = $this->totalCash + $distributor + $canteen;
    }
}
