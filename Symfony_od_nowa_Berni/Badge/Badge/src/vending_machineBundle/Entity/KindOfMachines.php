<?php

namespace vending_machineBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * KindOfMachines
 *
 * @ORM\Table(name="kind_of_machines")
 * @ORM\Entity(repositoryClass="vending_machineBundle\Repository\KindOfMachinesRepository")
 */
class KindOfMachines
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="earnedCash", type="decimal", precision=10, scale=2)
     */
    private $earnedCash;

    /**
     * @ORM\ManyToOne(targetEntity="typesOfservices", inversedBy="kindOfMachines")
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
     * Set name
     *
     * @param string $name
     *
     * @return KindOfMachines
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
     * Set earnedCash
     *
     * @param string $earnedCash
     *
     * @return KindOfMachines
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
     * @return KindOfMachines
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
