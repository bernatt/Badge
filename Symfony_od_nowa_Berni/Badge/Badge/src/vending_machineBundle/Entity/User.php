<?php

namespace vending_machineBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use vending_machineBundle\Entity\machine;

    /**
    * @ORM\Entity
    * @ORM\Table(name="fos_user")
     * @ORM\Entity(repositoryClass="vending_machineBundle\Repository\employeeRepository")
    */

    class User extends BaseUser

    {
        /**
         * @ORM\Id
         * @ORM\Column(type="integer")
         * @ORM\GeneratedValue(strategy="AUTO")
         */

        protected $id;

        /**
         * @var int
         *
         * @ORM\Column(name="badge_nr", type="integer", unique=true)
         */
        private $badgeNr;

        /**
         * @var float
         *
         * @ORM\Column(name="cash", type="decimal", precision=10, scale=2)
         */
        private $cash = 0;

        /**
         * @var string
         *
         * @ORM\Column(name="history", type="text", nullable=true)
         */
        private $history;


        public function __construct()
        {

            parent::__construct();
        }

    
    /**
     * Set cash
     *
     * @param string $cash
     *
     * @return User
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
     * Set badgeNr
     *
     * @param integer $badgeNr
     *
     * @return User
     */
    public function setBadgeNr($badgeNr)
    {
        $this->badgeNr = $badgeNr;

        return $this;
    }

    /**
     * Get badgeNr
     *
     * @return integer
     */
    public function getBadgeNr()
    {
        return $this->badgeNr;
    }

    /**
     * Set history
     *
     * @param string $history
     *
     * @return User
     */
    public function setHistory($history)
    {
        $this->history = $history;

        return $this;
    }

    /**
     * Get history
     *
     * @return string
     */
    public function getHistory()
    {
        return $this->history;
    }


        public function deposit( $amount)
        {
            $this->cash = $this->cash + $amount;
            return $this;
        }

        public function buyFromMachine($price)
        {
            $this->cash = $this->cash - $price;
            return $this;
        }

        public function addToHistory($history)
        {
            $this->history .= $history;
            return $this;
        }

        public function clearHistory()
        {
            $this->history = '';
        }

        public static function hereRoles()
        {
            return ['ROLE_USER','ROLE_ADMIN','ROLE_SUPER_ADMIN'];
        }
}
