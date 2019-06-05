<?php

namespace vending_machineBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

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
     * @ORM\OneToMany(targetEntity="Vegan", mappedBy="canteen")
     */
    private $vegan;

    /**
     * @ORM\OneToMany(targetEntity="Meat", mappedBy="canteen")
     */
    private $meat;

    /**
     * Canteen constructor.
     */
    public function __construct()
    {
        $this->vegan = new ArrayCollection();
        $this->meat = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->kindOfMeal;
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

    /**
     * Add vegan
     *
     * @param \vending_machineBundle\Entity\Vegan $vegan
     *
     * @return Canteen
     */
    public function addVegan(\vending_machineBundle\Entity\Vegan $vegan)
    {
        $this->vegan[] = $vegan;

        return $this;
    }

    /**
     * Remove vegan
     *
     * @param \vending_machineBundle\Entity\Vegan $vegan
     */
    public function removeVegan(\vending_machineBundle\Entity\Vegan $vegan)
    {
        $this->vegan->removeElement($vegan);
    }

    /**
     * Get vegan
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVegan()
    {
        return $this->vegan;
    }

    /**
     * Add meat
     *
     * @param \vending_machineBundle\Entity\Meat $meat
     *
     * @return Canteen
     */
    public function addMeat(\vending_machineBundle\Entity\Meat $meat)
    {
        $this->meat[] = $meat;

        return $this;
    }

    /**
     * Remove meat
     *
     * @param \vending_machineBundle\Entity\Meat $meat
     */
    public function removeMeat(\vending_machineBundle\Entity\Meat $meat)
    {
        $this->meat->removeElement($meat);
    }

    /**
     * Get meat
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMeat()
    {
        return $this->meat;
    }

    public static function costOfDinner($badge_color)
    {
        if ($badge_color = 'niebieski'){
            $price = 1;
        }
        elseif ($badge_color = 'zielony'){
            $price = 2;
        }
        else{
            $price = 10;
        }
        return $price;
    }

    public function buyDinner($badgeColor)
    {
        $this->earnedCash = $this->earnedCash + Canteen::costOfDinner($badgeColor);
    }

    public static function dateTranslate($day)
    {
        if ($day == 1){
            $curentDay = 'Poniedziałek';
        }
        elseif ($day == 2){
            $curentDay = 'Wtorek';
        }
        elseif ($day == 3){
            $curentDay = 'Środa';
        }
        elseif ($day == 4){
            $curentDay = 'Czwartek';
        }
        elseif ($day == 5){
            $curentDay = 'Piątek';
        }
        elseif ($day == 6){
            $curentDay = 'Sobota';
        }
        else{
            $curentDay = 'Niedziela';
        }

        return $curentDay;
    }

}
