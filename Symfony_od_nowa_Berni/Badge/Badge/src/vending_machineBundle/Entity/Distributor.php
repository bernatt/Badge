<?php

namespace vending_machineBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Distributor
 *
 * @ORM\Table(name="distributor")
 * @ORM\Entity(repositoryClass="vending_machineBundle\Repository\DistributorRepository")
 */
class Distributor
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
     * @ORM\Column(name="kindOfDistributor", type="string", length=255)
     */
    private $kindOfDistributor;

    /**
     * @var float
     *
     * @ORM\Column(name="earnedCash", type="decimal", precision=10, scale=2)
     */
    private $earnedCash = 0;


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
     * Set kindOfDistributor
     *
     * @param string $kindOfDistributor
     *
     * @return Distributor
     */
    public function setKindOfDistributor($kindOfDistributor)
    {
        $this->kindOfDistributor = $kindOfDistributor;

        return $this;
    }

    /**
     * Get kindOfDistributor
     *
     * @return string
     */
    public function getKindOfDistributor()
    {
        return $this->kindOfDistributor;
    }

    /**
     * Set earnedCash
     *
     * @param string $earnedCash
     *
     * @return Distributor
     */
    public function setEarnedCash($earnedCash)
    {
        $this->earnedCash = $earnedCash;

        return $this;
    }

    /**
     * Get earnedCash
     *
     * @return string
     */
    public function getEarnedCash()
    {
        return $this->earnedCash;
    }


    public function addMoney($amount)
    {
        $this->earnedCash = $this->earnedCash + $amount;
    }
}

