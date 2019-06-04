<?php

namespace vending_machineBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Canteen
 *
 * @ORM\Table(name="canteen")
 * @ORM\Entity(repositoryClass="vending_machineBundle\Repository\CanteenRepository")
 */
class Canteen
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
     * @ORM\Column(name="kindOfMeal", type="string", length=255)
     */
    private $kindOfMeal;

    /**
     * @var string
     *
     * @ORM\Column(name="earnedCash", type="decimal", precision=10, scale=2)
     */
    private $earnedCash = 0;

    /**
     * @ORM\ManyToOne(targetEntity="typesOfservices", inversedBy="canteen")
     * @ORM\JoinColumn(name="typeOfservice_id", referencedColumnName="id")
     */
    private $type;


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
     * Set kindOfMeal
     *
     * @param string $kindOfMeal
     *
     * @return Canteen
     */
    public function setKindOfMeal($kindOfMeal)
    {
        $this->kindOfMeal = $kindOfMeal;

        return $this;
    }

    /**
     * Get kindOfMeal
     *
     * @return string
     */
    public function getKindOfMeal()
    {
        return $this->kindOfMeal;
    }

    /**
     * Set earnedCash
     *
     * @param string $earnedCash
     *
     * @return Canteen
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

    /**
     * Set type
     *
     * @param \vending_machineBundle\Entity\typesOfservices $type
     *
     * @return Canteen
     */
    public function setType(\vending_machineBundle\Entity\typesOfservices $type = null)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return \vending_machineBundle\Entity\typesOfservices
     */
    public function getType()
    {
        return $this->type;
    }
}
