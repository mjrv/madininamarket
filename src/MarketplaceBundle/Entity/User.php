<?php
// src/AppBundle/Entity/User.php

namespace MarketplaceBundle\Entity;

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
    * @ORM\OneToMany(targetEntity="MarketplaceBundle\Entity\UserAdress",mappedBy="user")
    * @ORM\JoinColumn(nullable=true)
    */
    private $adress;

    public function __construct()
    {
        parent::__construct();
        $this->adress= new \Doctrine\Common\Collections\ArrayCollection();
        // your own logic
    }

    /**
     * Add adress
     *
     * @param \MarketplaceBundle\Entity\UserAdress $adress
     *
     * @return User
     */
    public function addAdress(\MarketplaceBundle\Entity\UserAdress $adress)
    {
        $this->adress[] = $adress;

        return $this;
    }

    /**
     * Remove adress
     *
     * @param \MarketplaceBundle\Entity\UserAdress $adress
     */
    public function removeAdress(\MarketplaceBundle\Entity\UserAdress $adress)
    {
        $this->adress->removeElement($adress);
    }

    /**
     * Get adress
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAdress()
    {
        return $this->adress;
    }
}
