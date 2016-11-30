<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Validator\Constraints as AppAssert;

/**
 * fingeringOffline
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\FingeringOfflineRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class FingeringOffline
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
     * @var datetime
     *
     * @ORM\Column(name="createdAt", type="datetime",nullable=true)
     */
    private $createdAt;


    /**
     * @ORM\ManyToOne(targetEntity="Instrument")
     * @ORM\JoinColumn(name="instrument", referencedColumnName="id")
     */
    private $instrument;
    /**
     * @ORM\ManyToOne(targetEntity="Fingering")
     * @ORM\JoinColumn(name="fingering", referencedColumnName="id")
     */
    private $fingering;


    /**
     * @ORM\ManyToMany(targetEntity="Yx", inversedBy="fingeringOffline")
     * @ORM\JoinTable(name="fingeringOfflines_Yxs")
     * @ORM\OrderBy({"y" = "ASC"})
     */
    private $yxs;

    /**
     * @ORM\ManyToOne(targetEntity="WesternSystem")
     * @ORM\JoinColumn(name="root", referencedColumnName="id")
     */
    private $root;

    /**
     * @ORM\ManyToOne(targetEntity="Scale")
     * @ORM\JoinColumn(name="scale", referencedColumnName="id")
     */
    private $scale;


    /**
     * @ORM\OneToMany(targetEntity="BasketBundle\Entity\FingeringBasketsFingerings", mappedBy="fingeringOffline", cascade={"persist", "remove"} )
     */
    protected $fingeringbaskets_fingerings;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->yxs = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set instrument
     *
     * @param \AppBundle\Entity\Instrument $instrument
     *
     * @return FingeringOffline
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
     * Set fingering
     *
     * @param \AppBundle\Entity\Fingering $fingering
     *
     * @return FingeringOffline
     */
    public function setFingering(\AppBundle\Entity\Fingering $fingering = null)
    {
        $this->fingering = $fingering;

        return $this;
    }

    /**
     * Get fingering
     *
     * @return \AppBundle\Entity\Fingering
     */
    public function getFingering()
    {
        return $this->fingering;
    }

    /**
     * Add yx
     *
     * @param \AppBundle\Entity\Yx $yx
     *
     * @return FingeringOffline
     */
    public function addYx(\AppBundle\Entity\Yx $yx)
    {
        $this->yxs[] = $yx;

        return $this;
    }

    /**
     * Remove yx
     *
     * @param \AppBundle\Entity\Yx $yx
     */
    public function removeYx(\AppBundle\Entity\Yx $yx)
    {
        $this->yxs->removeElement($yx);
    }

    /**
     * Get yxs
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getYxs()
    {
        return $this->yxs;
    }

    /**
     * Set root
     *
     * @param \AppBundle\Entity\WesternSystem $root
     *
     * @return FingeringOffline
     */
    public function setRoot(\AppBundle\Entity\WesternSystem $root = null)
    {
        $this->root = $root;

        return $this;
    }

    /**
     * Get root
     *
     * @return \AppBundle\Entity\WesternSystem
     */
    public function getRoot()
    {
        return $this->root;
    }

    /**
     * Set scale
     *
     * @param \AppBundle\Entity\Scale $scale
     *
     * @return FingeringOffline
     */
    public function setScale(\AppBundle\Entity\Scale $scale = null)
    {
        $this->scale = $scale;

        return $this;
    }

    /**
     * Get scale
     *
     * @return \AppBundle\Entity\Scale
     */
    public function getScale()
    {
        return $this->scale;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return FingeringOffline
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
     * Add fingeringbasketsFingering
     *
     * @param \BasketBundle\Entity\FingeringBasketsFingerings $fingeringbasketsFingering
     *
     * @return FingeringOffline
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
}
