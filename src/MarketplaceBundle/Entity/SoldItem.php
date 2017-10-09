<?php

namespace MarketplaceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * SoldItem
 *
 * @ORM\Table(name="sold_item")
 * @ORM\Entity(repositoryClass="MarketplaceBundle\Repository\SoldItemRepository")
 */
class SoldItem
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
     * @var int
     *
     * @ORM\Column(name="quantity", type="integer")
     */
    private $quantity;

    /**
    *@ORM\Column(name="item", type="integer")
    */
    private $item;

    /**
    *@var \Datetime
    *@Gedmo\Timestampable(on="create")
    *@ORM\Column(name="sold_at", type="date", nullable=true)
    */
    private $soldAt;




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
     * Set quantity
     *
     * @param integer $quantity
     *
     * @return SoldItem
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set item
     *
     * @param integer $item
     *
     * @return SoldItem
     */
    public function setItem($item)
    {
        $this->item = $item;

        return $this;
    }

    /**
     * Get item
     *
     * @return int
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * @return \Datetime
     */
    public function getSoldAt()
    {
        return $this->soldAt;
    }

    /**
     * @param \Datetime $soldAt
     *
     * @return self
     */
    public function setSoldAt(\Datetime $soldAt)
    {
        $this->soldAt = $soldAt;

        return $this;
    }
}

