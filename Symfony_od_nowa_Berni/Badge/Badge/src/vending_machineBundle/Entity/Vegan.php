<?php

namespace vending_machineBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Vegan
 *
 * @ORM\Table(name="vegan")
 * @ORM\Entity(repositoryClass="vending_machineBundle\Repository\VeganRepository")
 */
class Vegan
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
     * @ORM\Column(name="day", type="string", length=255, unique=true)
     */
    private $day;

    /**
     * @var string
     *
     * @ORM\Column(name="dish", type="string", length=255)
     */
    private $dish;

    /**
     * @var int
     *
     * @ORM\Column(name="sumOfPoints", type="integer", nullable=true)
     */
    private $sumOfPoints;

    /**
     * @var int
     *
     * @ORM\Column(name="numberOfVoters", type="integer", nullable=true)
     */
    private $numberOfVoters;

    /**
     * @var string
     *
     * @ORM\Column(name="rating", type="float", nullable=true)
     */
    private $rating;

    /**
     * @ORM\ManyToOne(targetEntity="Canteen", inversedBy="vegan")
     * @ORM\JoinColumn(name="kindOfmeal_id", referencedColumnName="id")
     */
    private $canteen;


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
     * Set day
     *
     * @param string $day
     *
     * @return Vegan
     */
    public function setDay($day)
    {
        $this->day = $day;

        return $this;
    }

    /**
     * Get day
     *
     * @return string
     */
    public function getDay()
    {
        return $this->day;
    }

    /**
     * Set dish
     *
     * @param string $dish
     *
     * @return Vegan
     */
    public function setDish($dish)
    {
        $this->dish = $dish;

        return $this;
    }

    /**
     * Get dish
     *
     * @return string
     */
    public function getDish()
    {
        return $this->dish;
    }

    /**
     * Set sumOfPoints
     *
     * @param integer $sumOfPoints
     *
     * @return Vegan
     */
    public function setSumOfPoints($sumOfPoints)
    {
        $this->sumOfPoints = $sumOfPoints;

        return $this;
    }

    /**
     * Get sumOfPoints
     *
     * @return int
     */
    public function getSumOfPoints()
    {
        return $this->sumOfPoints;
    }

    /**
     * Set numberOfVoters
     *
     * @param integer $numberOfVoters
     *
     * @return Vegan
     */
    public function setNumberOfVoters($numberOfVoters)
    {
        $this->numberOfVoters = $numberOfVoters;

        return $this;
    }

    /**
     * Get numberOfVoters
     *
     * @return int
     */
    public function getNumberOfVoters()
    {
        return $this->numberOfVoters;
    }

    /**
     * Set rating
     *
     * @param string $rating
     *
     * @return Vegan
     */
    public function setRating($rating)
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * Get rating
     *
     * @return string
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * Set canteen
     *
     * @param \vending_machineBundle\Entity\Canteen $canteen
     *
     * @return Vegan
     */
    public function setCanteen(\vending_machineBundle\Entity\Canteen $canteen = null)
    {
        $this->canteen = $canteen;

        return $this;
    }

    /**
     * Get canteen
     *
     * @return \vending_machineBundle\Entity\Canteen
     */
    public function getCanteen()
    {
        return $this->canteen;
    }

    public function addVoters()
    {
        $this->numberOfVoters = $this->numberOfVoters + 1;
        return $this;
    }

    public function addRatePoint($rate)
    {
        $this->sumOfPoints = $this->sumOfPoints + $rate;
        return $this;
    }

    public function calculateRating()
    {
        $this->rating = $this->sumOfPoints / $this->numberOfVoters;
        return $this;
    }
}
