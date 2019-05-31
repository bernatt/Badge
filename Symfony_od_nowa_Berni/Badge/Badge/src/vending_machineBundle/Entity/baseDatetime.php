<?php

use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Entity
*
*
*/

abstract class baseDatetime
{


    /**
    * @var datetime $created
    *
    * @ORM\Column(type="datetime")
    */
    protected $created;

    /**
    * @var datetime $updated
    *
    * @ORM\Column(type="datetime", nullable = true)
    */
    protected $updated;


    /**
    * Gets triggered only on insert
    * @ORM\PrePersist
    */
    public function onPrePersist()
    {
        $this->created = new \DateTime("now");
    }

    /**
    * Gets triggered every time on update

    * @ORM\PreUpdate
    */
    public function onPreUpdate()
    {
         $this->updated = new \DateTime("now");
    }

}