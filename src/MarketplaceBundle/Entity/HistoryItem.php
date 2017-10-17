<?php

namespace MarketplaceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * HistoryItem
 *
 * @ORM\Table(name="history_item")
 * @ORM\Entity(repositoryClass="MarketplaceBundle\Repository\HistoryItemRepository")
 */
class HistoryItem
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
    * @ORM\ManyToOne(targetEntity="MarketplaceBundle\Entity\User")
    */
    private $user;

    /**
    *@ORM\ManyToOne(targetEntity="MarketplaceBundle\Entity\Items", inversedBy="history", cascade={"all"})
    */
    private $item;
   
     /**
    *@var \Datetime
    *@Gedmo\Timestampable(on="create")
    *@ORM\Column(name="update_at", type="datetimetz", nullable=true)
    */
    private $updateAt;

   
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
     * Set user
     *
     * @param string $user
     *
     * @return HistoryItem
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return string
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set updateAt
     *
     * @param \DateTime $updateAt
     *
     * @return HistoryItem
     */
    public function setUpdateAt($updateAt)
    {
        $this->updateAt = $updateAt;

        return $this;
    }

    /**
     * Get updateAt
     *
     * @return \DateTime
     */
    public function getUpdateAt()
    {
        return $this->updateAt;
    }

    /**
     * Set item
     *
     * @param \MarketplaceBundle\Entity\Items $item
     *
     * @return HistoryItem
     */
    public function setItem(\MarketplaceBundle\Entity\Items $item = null)
    {
        $this->item = $item;

        return $this;
    }

    /**
     * Get item
     *
     * @return \MarketplaceBundle\Entity\Items
     */
    public function getItem()
    {
        return $this->item;
    }
}
