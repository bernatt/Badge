<?php

namespace vending_machineBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * employee
 *
 * @ORM\Table(name="employee")
 * @ORM\Entity(repositoryClass="vending_machineBundle\Repository\employeeRepository")
 */
class employee
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
     * @ORM\Column(name="name", type="string", length=50)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="surname", type="string", length=50)
     */
    private $surname;

    /**
     * @var int
     *
     * @ORM\Column(name="badge_nr", type="integer", unique=true)
     */
    private $badgeNr;

    /**
     * @var string
     *
     * @ORM\Column(name="cash", type="decimal", precision=10, scale=2)
     */
    private $cash = 0;


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
     * @return employee
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
     * Set surname
     *
     * @param string $surname
     *
     * @return employee
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;

        return $this;
    }

    /**
     * Get surname
     *
     * @return string
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * Set badgeNr
     *
     * @param integer $badgeNr
     *
     * @return employee
     */
    public function setBadgeNr($badgeNr)
    {
        $this->badgeNr = $badgeNr;

        return $this;
    }

    /**
     * Get badgeNr
     *
     * @return int
     */
    public function getBadgeNr()
    {
        return $this->badgeNr;
    }

    /**
     * Set cash
     *
     * @param string $cash
     *
     * @return employee
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

    public function deposit($amount)
    {
        $this->cash = $this->cash + $amount;
        return $this;
    }
}

