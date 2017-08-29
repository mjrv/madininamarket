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

    /**
    * @ORM\ManyToMany(targetEntity="MarketplaceBundle\Entity\Shop",mappedBy="user")
    */
    private $shop;

    /**
    *@ORM\Column(nullable=true, type="string")
    */
    private $lastname;

    /**
    *@ORM\Column(nullable=true, type="string")
    */
    private $firstname;

    public function __construct()
    {
        parent::__construct();
        $this->adress= new \Doctrine\Common\Collections\ArrayCollection();
        $this->shop= new \Doctrine\Common\Collections\ArrayCollection();
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

    /**
     * Add shop
     *
     * @param \MarketplaceBundle\Entity\Shop $shop
     *
     * @return User
     */
    public function addShop(\MarketplaceBundle\Entity\Shop $shop)
    {
        $this->shop[] = $shop;

        return $this;
    }

    /**
     * Remove shop
     *
     * @param \MarketplaceBundle\Entity\Shop $shop
     */
    public function removeShop(\MarketplaceBundle\Entity\Shop $shop)
    {
        $this->shop->removeElement($shop);
    }

    /**
     * Get shop
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getShop()
    {
        return $this->shop;
    }

    /**
     * @return mixed
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param mixed $lastname
     *
     * @return self
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param mixed $firstname
     *
     * @return self
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }
}
