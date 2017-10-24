<?php

namespace MarketplaceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ShipmentPrice
 *
 * @ORM\Table(name="shipment_price")
 * @ORM\Entity(repositoryClass="MarketplaceBundle\Repository\ShipmentPriceRepository")
 */
class ShipmentPrice
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
     * @ORM\Column(name="type", type="string", length=255, unique=true)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="typeInfo", type="string", length=255)
     */
    private $typeInfo;

    /**
     * @var float
     *
     * @ORM\Column(name="price1", type="float")
     */
    private $price1;

    /**
     * @var float
     *
     * @ORM\Column(name="price2", type="float")
     */
    private $price2;

    /**
     * @var float
     *
     * @ORM\Column(name="price3", type="float")
     */
    private $price3;

    /**
     * @var float
     *
     * @ORM\Column(name="price4", type="float")
     */
    private $price4;

     /**
     * @var float
     *
     * @ORM\Column(name="nextItem", type="float")
     */
    private $nextItem;

    /**
     * @ORM\OneToMany(targetEntity="MarketplaceBundle\Entity\Items", mappedBy="shipmentPrice")
     */
    private $items;

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
     * Set type
     *
     * @param string $type
     *
     * @return ShipmentPrice
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set typeInfo
     *
     * @param string $typeInfo
     *
     * @return ShipmentPrice
     */
    public function setTypeInfo($typeInfo)
    {
        $this->typeInfo = $typeInfo;

        return $this;
    }

    /**
     * Get typeInfo
     *
     * @return string
     */
    public function getTypeInfo()
    {
        return $this->typeInfo;
    }

    /**
     * Set price1
     *
     * @param float $price1
     *
     * @return ShipmentPrice
     */
    public function setPrice1($price1)
    {
        $this->price1 = $price1;

        return $this;
    }

    /**
     * Get price1
     *
     * @return float
     */
    public function getPrice1()
    {
        return $this->price1;
    }

    /**
     * Set price2
     *
     * @param float $price2
     *
     * @return ShipmentPrice
     */
    public function setPrice2($price2)
    {
        $this->price2 = $price2;

        return $this;
    }

    /**
     * Get price2
     *
     * @return float
     */
    public function getPrice2()
    {
        return $this->price2;
    }

    /**
     * Set price3
     *
     * @param float $price3
     *
     * @return ShipmentPrice
     */
    public function setPrice3($price3)
    {
        $this->price3 = $price3;

        return $this;
    }

    /**
     * Get price3
     *
     * @return float
     */
    public function getPrice3()
    {
        return $this->price3;
    }

    /**
     * Set price4
     *
     * @param float $price4
     *
     * @return ShipmentPrice
     */
    public function setPrice4($price4)
    {
        $this->price4 = $price4;

        return $this;
    }

    /**
     * Get price4
     *
     * @return float
     */
    public function getPrice4()
    {
        return $this->price4;
    }

    /**
     * @return float
     */
    public function getNextItem()
    {
        return $this->nextItem;
    }

    /**
     * @param float $nextItem
     *
     * @return self
     */
    public function setNextItem($nextItem)
    {
        $this->nextItem = $nextItem;

        return $this;
    }


    /**
     * @return mixed
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param mixed \MarketplaceBundle\Entity\Items $items
     *
     * @return self
     */
    public function setItems(\MarketplaceBundle\Entity\Items $items)
    {
        $this->items = $items;

        return $this;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->items = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add item
     *
     * @param \MarketplaceBundle\Entity\Items $item
     *
     * @return ShipmentPrice
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
}
