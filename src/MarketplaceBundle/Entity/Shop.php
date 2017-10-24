<?php

namespace MarketplaceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Shop
 *
 * @ORM\Table(name="shop")
 * @ORM\Entity(repositoryClass="MarketplaceBundle\Repository\ShopRepository")
 * @UniqueEntity("commercialName",message="Ce nom commerciale est déjà utilisé!!")
 * @UniqueEntity("immatriculation")
 * @UniqueEntity("prefixeRef")
 */
class Shop
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
     * @ORM\Column(name="commercialName", type="string", length=255, unique=true)
     */
    private $commercialName;

    /**
     * @var string
     *
     * @ORM\Column(name="raisonSocial", type="string", length=255)
     */
    private $raisonSocial;

    /**
     * @var string
     *
     * @ORM\Column(name="immatriculation", type="string", length=255, unique=true)
     * 
     */
    private $immatriculation;

    /**
     * @var string
     *
     * @ORM\Column(name="apeCode", type="string", length=255)
     */
    private $apeCode;

    /**
     * @var string
     *
     * @ORM\Column(name="nameGerant", type="string", length=255)
     */
    private $nameGerant;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=255)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="phone2", type="string", length=255, nullable=true)
     */
    private $phone2;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="adress", type="string", length=255)
     */
    private $adress;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=255)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="zipcode", type="string", length=255)
     */
    private $zipcode;

    /**
     * @var string
     *
     * @ORM\Column(name="logo", type="string", length=255)
     */
    private $logo;

    /**
     * @var string
     *
     * @ORM\Column(name="cover", type="string", length=255)
     */
    private $cover;


    /**
    *@ORM\ManyToMany(targetEntity="MarketplaceBundle\Entity\User",inversedBy="shop",cascade={"persist"})
    */
    private $user;

    /**
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $active;

    /**
    * @ORM\OneToMany(targetEntity="MarketplaceBundle\Entity\Items",mappedBy="shop")
    */
    private $items;

    /**
     * @Gedmo\Slug(fields={"commercialName"})
     * @ORM\column(length=255, unique=true)
     */
    private $slug;

    /**
     * @ORM\Column(name="prefixeRef", type="string", length=3, unique=true)
     */
    private $prefixeRef;

    /**
     * @ORM\Column(name="generateAutoRef", type="integer",length=6, options={"default":0})
     */
    private $generateAutoRef;

    /**
     * @ORM\Column(name="retrait", type="boolean")
     * @Assert\NotNull(message="Merci de choisir entre oui et non")
     */
    private $retraitMag;

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
     * Set commercialName
     *
     * @param string $commercialName
     *
     * @return Shop
     */
    public function setCommercialName($commercialName)
    {
        $this->commercialName = $commercialName;

        return $this;
    }

    /**
     * Get commercialName
     *
     * @return string
     */
    public function getCommercialName()
    {
        return $this->commercialName;
    }

    /**
     * Set raisonSocial
     *
     * @param string $raisonSocial
     *
     * @return Shop
     */
    public function setRaisonSocial($raisonSocial)
    {
        $this->raisonSocial = $raisonSocial;

        return $this;
    }

    /**
     * Get raisonSocial
     *
     * @return string
     */
    public function getRaisonSocial()
    {
        return $this->raisonSocial;
    }

    /**
     * Set immatriculation
     *
     * @param string $immatriculation
     *
     * @return Shop
     */
    public function setImmatriculation($immatriculation)
    {
        $this->immatriculation = $immatriculation;

        return $this;
    }

    /**
     * Get immatriculation
     *
     * @return string
     */
    public function getImmatriculation()
    {
        return $this->immatriculation;
    }

    /**
     * Set apeCode
     *
     * @param string $apeCode
     *
     * @return Shop
     */
    public function setApeCode($apeCode)
    {
        $this->apeCode = $apeCode;

        return $this;
    }

    /**
     * Get apeCode
     *
     * @return string
     */
    public function getApeCode()
    {
        return $this->apeCode;
    }

    /**
     * Set nameGerant
     *
     * @param string $nameGerant
     *
     * @return Shop
     */
    public function setNameGerant($nameGerant)
    {
        $this->nameGerant = $nameGerant;

        return $this;
    }

    /**
     * Get nameGerant
     *
     * @return string
     */
    public function getNameGerant()
    {
        return $this->nameGerant;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return Shop
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set phone2
     *
     * @param string $phone2
     *
     * @return Shop
     */
    public function setPhone2($phone2)
    {
        $this->phone2 = $phone2;

        return $this;
    }

    /**
     * Get phone2
     *
     * @return string
     */
    public function getPhone2()
    {
        return $this->phone2;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Shop
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set adress
     *
     * @param string $adress
     *
     * @return Shop
     */
    public function setAdress($adress)
    {
        $this->adress = $adress;

        return $this;
    }

    /**
     * Get adress
     *
     * @return string
     */
    public function getAdress()
    {
        return $this->adress;
    }

    /**
     * Set city
     *
     * @param string $city
     *
     * @return Shop
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set zipcode
     *
     * @param string $zipcode
     *
     * @return Shop
     */
    public function setZipcode($zipcode)
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    /**
     * Get zipcode
     *
     * @return string
     */
    public function getZipcode()
    {
        return $this->zipcode;
    }

    /**
     * Set logo
     *
     * @param string $logo
     *
     * @return Shop
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * Get logo
     *
     * @return string
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * Set cover
     *
     * @param string $cover
     *
     * @return Shop
     */
    public function setCover($cover)
    {
        $this->cover = $cover;

        return $this;
    }

    /**
     * Get cover
     *
     * @return string
     */
    public function getCover()
    {
        return $this->cover;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->user = new \Doctrine\Common\Collections\ArrayCollection();
        $this->items = new \Doctrine\Common\Collections\ArrayCollection();
    }

    

    /**
     * Set active
     *
     * @param boolean $active
     *
     * @return Shop
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Add user
     *
     * @param \MarketplaceBundle\Entity\User $user
     *
     * @return Shop
     */
    public function addUser(\MarketplaceBundle\Entity\User $user)
    {
        $this->user[] = $user;

        return $this;
    }

    /**
     * Remove user
     *
     * @param \MarketplaceBundle\Entity\User $user
     */
    public function removeUser(\MarketplaceBundle\Entity\User $user)
    {
        $this->user->removeElement($user);
    }

    /**
     * Get user
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Add item
     *
     * @param \MarketplaceBundle\Entity\Items $item
     *
     * @return Shop
     */
    public function addItem(\MarketplaceBundle\Entity\Items $item)
    {
        $this->items[] = $item;

        return $this;
    }

    /**
     * Remove item
     *
     * @param \MarketplaceBundle\Entity\Items $item
     */
    public function removeItem(\MarketplaceBundle\Entity\Items $item)
    {
        $this->items->removeElement($item);
    }

    /**
     * Get items
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @return mixed
     */
    public function getGenerateAutoRef()
    {
        return $this->generateAutoRef;
    }

    /**
     * @param mixed $generateAutoRef
     *
     * @return self
     */
    public function setGenerateAutoRef($generateAutoRef)
    {
        $this->generateAutoRef = $generateAutoRef;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPrefixeRef()
    {
        return $this->prefixeRef;
    }

    /**
     * @param mixed $prefixeRef
     *
     * @return self
     */
    public function setPrefixeRef($prefixeRef)
    {
        $this->prefixeRef = $prefixeRef;

        return $this;
    }

   

    /**
     * @return mixed
     */
    public function getRetraitMag()
    {
        return $this->retraitMag;
    }

    /**
     * @param mixed $retraitMag
     *
     * @return self
     */
    public function setRetraitMag($retraitMag)
    {
        $this->retraitMag = $retraitMag;

        return $this;
    }
}
