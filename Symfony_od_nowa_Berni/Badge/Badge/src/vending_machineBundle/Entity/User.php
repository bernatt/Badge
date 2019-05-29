<?php

namespace vending_machineBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

    /**
    * @ORM\Entity
    * @ORM\Table(name="fos_user")
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
         * @var string
         *
         * @ORM\Column(name="cash", type="decimal", precision=10, scale=2)
         */
        private $cash = 0;

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

    public function deposit($amount)
    {
        $this->cash = $this->cash + $amount;
        return $this;
    }
}
