<?php

namespace MarketplaceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Items
 *
 * @ORM\Table(name="items")
 * @ORM\Entity(repositoryClass="MarketplaceBundle\Repository\ItemsRepository")
 */
class Items
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
    *@Gedmo\Timestampable(on="create")
    *@ORM\Column(type="datetime")
    */
    private $createdAt;

    /**
     * @var string
     *
     * @ORM\Column(name="reference", type="string", length=255)
     */
    private $reference;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="MarketplaceBundle\Entity\Picture",mappedBy="Items", cascade={"persist","remove"})
     * @var  Picture[]
     */
    private $picture;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var float
     *
     * @ORM\Column(name="price_ht", type="float", length=255)
     */
    private $priceHt;

    /**
     * @var float
     *
     * @ORM\Column(name="discount", type="float", nullable=true)
     */
    private $discount;

    /**
     * @var int
     *
     * @ORM\Column(name="stock", type="integer")
     */
    private $stock;

     /**
     * @ORM\ManyToOne(targetEntity="MarketplaceBundle\Entity\Tva",cascade={"persist","remove"})
     */
    private $tva;


     /**
     * @ORM\ManyToOne(targetEntity="MarketplaceBundle\Entity\Category",cascade={"persist","remove"})
     */
    private $category;

    /**
     * @var int
     *
     * @ORM\Column(name="verify", type="integer")
     */
    private $verify;//valeur acceptee 0 pour en attente 1 accepter 2 pour refuser

    /**
    *@ORM\OneToMany(targetEntity="MarketplaceBundle\Entity\HistoryItem", cascade={"persist"})
    *@ORM\column(nullable=true)
    */
    private $history;

    /**
    *@ORM\ManyToOne(targetEntity="MarketplaceBundle\Entity\Shop", inversedBy="items")
    */
    private $shop;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->picture = new \Doctrine\Common\Collections\ArrayCollection();
        // $this->shop = new \Doctrine\Common\Collections\ArrayCollection();
    }

    

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Items
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set reference
     *
     * @param string $reference
     *
     * @return Items
     */
    public function setReference($reference)
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * Get reference
     *
     * @return string
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Items
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
     * Set description
     *
     * @param string $description
     *
     * @return Items
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set priceHt
     *
     * @param float $priceHt
     *
     * @return Items
     */
    public function setPriceHt($priceHt)
    {
        $this->priceHt = $priceHt;

        return $this;
    }

    /**
     * Get priceHt
     *
     * @return float
     */
    public function getPriceHt()
    {
        return $this->priceHt;
    }

    /**
     * Set discount
     *
     * @param float $discount
     *
     * @return Items
     */
    public function setDiscount($discount)
    {
        $this->discount = $discount;

        return $this;
    }

    /**
     * Get discount
     *
     * @return float
     */
    public function getDiscount()
    {
        return $this->discount;
    }

    /**
     * Set stock
     *
     * @param integer $stock
     *
     * @return Items
     */
    public function setStock($stock)
    {
        $this->stock = $stock;

        return $this;
    }

    /**
     * Get stock
     *
     * @return integer
     */
    public function getStock()
    {
        return $this->stock;
    }

    /**
     * Set verify
     *
     * @param integer $verify
     *
     * @return Items
     */
    public function setVerify($verify)
    {
        $this->verify = $verify;

        return $this;
    }

    /**
     * Get verify
     *
     * @return integer
     */
    public function getVerify()
    {
        return $this->verify;
    }

    /**
     * Set history
     *
     * @param string $history
     *
     * @return Items
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

    /**
     * Add picture
     *
     * @param \MarketplaceBundle\Entity\Picture $picture
     *
     * @return Items
     */
    public function addPicture(\MarketplaceBundle\Entity\Picture $picture)
    {
        $this->picture[] = $picture;

        return $this;
    }

    /**
     * Remove picture
     *
     * @param \MarketplaceBundle\Entity\Picture $picture
     */
    public function removePicture(\MarketplaceBundle\Entity\Picture $picture)
    {
        $this->picture->removeElement($picture);
    }

    /**
     * Get picture
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * Set tva
     *
     * @param \MarketplaceBundle\Entity\Tva $tva
     *
     * @return Items
     */
    public function setTva(\MarketplaceBundle\Entity\Tva $tva = null)
    {
        $this->tva = $tva;

        return $this;
    }

    /**
     * Get tva
     *
     * @return \MarketplaceBundle\Entity\Tva
     */
    public function getTva()
    {
        return $this->tva;
    }

    /**
     * Set category
     *
     * @param \MarketplaceBundle\Entity\Category $category
     *
     * @return Items
     */
    public function setCategory(\MarketplaceBundle\Entity\Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \MarketplaceBundle\Entity\Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set shop
     *
     * @param \MarketplaceBundle\Entity\Shop $shop
     *
     * @return Items
     */
    public function setShop(\MarketplaceBundle\Entity\Shop $shop = null)
    {
        $this->shop = $shop;

        return $this;
    }

    /**
     * Get shop
     *
     * @return \MarketplaceBundle\Entity\Shop
     */
    public function getShop()
    {
        return $this->shop;
    }
}
