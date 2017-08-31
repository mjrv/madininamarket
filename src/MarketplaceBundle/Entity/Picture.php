<?php

namespace MarketplaceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Upload\UploadBundle\Annotation\Uploadable;
use Upload\UploadBundle\Annotation\UploadableField;

/**
 * Picture
 *
 * @ORM\Table(name="picture")
 * @ORM\Entity(repositoryClass="MarketplaceBundle\Repository\PicturesRepository")
 * @Uploadable
 */
class Picture
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
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(name="url", type="string", length=255)
     */
    private $url;


    private $file;

    /**
    *@var \Datetime
    #*@Gedmo\Timestampable(on="create")
    *@ORM\Column(name="updatedAt", type="datetime", nullable=true)
    */
    private $updatedAt;

    /**
    * @ORM\ManyToOne(targetEntity="MarketplaceBundle\Entity\Items",inversedBy="picture", cascade={"persist","remove"})
    * @var  Items[]
    */
    private $items;


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
     * Set name
     *
     * @param string $name
     *
     * @return Picture
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
     * Set url
     *
     * @param string $url
     *
     * @return Picture
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Picture
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set items
     *
     * @param \MarketplaceBundle\Entity\Items $items
     *
     * @return Picture
     */
    public function setItems(\MarketplaceBundle\Entity\Items $items = null)
    {
        $this->items = $items;

        return $this;
    }

    /**
     * Get items
     *
     * @return \MarketplaceBundle\Entity\Items
     */
    public function getItems()
    {
        return $this->items;
    }
}
