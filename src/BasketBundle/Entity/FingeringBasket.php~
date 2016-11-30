<?php

namespace BasketBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * FingeringBasket
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="BasketBundle\Entity\FingeringBasketRepository")
 */
class FingeringBasket
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="fingeringbaskets")
     * @ORM\JoinColumn(name="createdBy", referencedColumnName="id")
     */
    protected $createdBy;

    /**
     * @var boolean
     *
     * @ORM\Column(name="private", type="boolean" , nullable=true, unique=false)
     */
    private $private;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string" , nullable=true, unique=false)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string" , nullable=true, unique=false)
     */
    private $description;

    /**
     * @var datetime
     *
     * @ORM\Column(name="createdAt", type="datetime",nullable=true)
     */
    private $createdAt;

    /**
     * @var datetime
     *
     * @ORM\Column(name="updatedAt", type="datetime",nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Instrument")
     * @ORM\JoinColumn(name="instrument", referencedColumnName="id")
     */
    private $instrument;

    /**
     * @ORM\OneToMany(targetEntity="FingeringBasketsFingerings", mappedBy="fingeringbasket" ,cascade={"remove","persist"})
     */
    private $fingeringbaskets_fingerings;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->fingeringbaskets_fingerings = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set title
     *
     * @param string $title
     *
     * @return FingeringBasket
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return FingeringBasket
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return FingeringBasket
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
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return FingeringBasket
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
     * Set createdBy
     *
     * @param \AppBundle\Entity\User $createdBy
     *
     * @return FingeringBasket
     */
    public function setCreatedBy(\AppBundle\Entity\User $createdBy = null)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Get createdBy
     *
     * @return \AppBundle\Entity\User
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * Set instrument
     *
     * @param \AppBundle\Entity\Instrument $instrument
     *
     * @return FingeringBasket
     */
    public function setInstrument(\AppBundle\Entity\Instrument $instrument = null)
    {
        $this->instrument = $instrument;

        return $this;
    }

    /**
     * Get instrument
     *
     * @return \AppBundle\Entity\Instrument
     */
    public function getInstrument()
    {
        return $this->instrument;
    }

    /**
     * Add fingeringbasketsFingering
     *
     * @param \BasketBundle\Entity\FingeringBasketsFingerings $fingeringbasketsFingering
     *
     * @return FingeringBasket
     */
    public function addFingeringbasketsFingering(\BasketBundle\Entity\FingeringBasketsFingerings $fingeringbasketsFingering)
    {
        $this->fingeringbaskets_fingerings[] = $fingeringbasketsFingering;

        return $this;
    }

    /**
     * Remove fingeringbasketsFingering
     *
     * @param \BasketBundle\Entity\FingeringBasketsFingerings $fingeringbasketsFingering
     */
    public function removeFingeringbasketsFingering(\BasketBundle\Entity\FingeringBasketsFingerings $fingeringbasketsFingering)
    {
        $this->fingeringbaskets_fingerings->removeElement($fingeringbasketsFingering);
    }

    /**
     * Get fingeringbasketsFingerings
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFingeringbasketsFingerings()
    {
        return $this->fingeringbaskets_fingerings;
    }

    /**
     * Set private
     *
     * @param boolean $private
     *
     * @return FingeringBasket
     */
    public function setPrivate($private)
    {
        $this->private = $private;

        return $this;
    }

    /**
     * Get private
     *
     * @return boolean
     */
    public function getPrivate()
    {
        return $this->private;
    }
}
