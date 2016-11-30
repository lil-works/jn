<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Validator\Constraints as AppAssert;

/**
 * Yx
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\YxRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Yx
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
     * @ORM\ManyToMany(targetEntity="FingeringOffline", mappedBy="yxs")
     */
    private $fingeringOffline;

    /**
     * @var int
     *
     * @ORM\Column(name="digitA", type="integer" , nullable=true)
     */
    private $digitA;
    /**
     * @var int
     *
     * @ORM\Column(name="y", type="integer" , nullable=false)
     */
    private $y;

    /**
     * @var int
     *
     * @ORM\Column(name="x", type="integer" , nullable=false)
     */
    private $x;


    /**
     * @var int
     *
     * @ORM\Column(name="lh", type="integer" , nullable=true)
     */
    private $lh;

    /**
     * @var int
     *
     * @ORM\Column(name="rh", type="integer" , nullable=true)
     */
    private $rh;

    
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Intervale" )
     * @ORM\JoinColumn(name="intervale", referencedColumnName="id")
     * */
    protected $intervale;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\WesternSystem" )
     * @ORM\JoinColumn(name="westernSystem", referencedColumnName="id")
     * */
    protected $westernSystem;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->fingeringOffline = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set y
     *
     * @param integer $y
     *
     * @return Yx
     */
    public function setY($y)
    {
        $this->y = $y;

        return $this;
    }

    /**
     * Get y
     *
     * @return integer
     */
    public function getY()
    {
        return $this->y;
    }

    /**
     * Set x
     *
     * @param integer $x
     *
     * @return Yx
     */
    public function setX($x)
    {
        $this->x = $x;

        return $this;
    }

    /**
     * Get x
     *
     * @return integer
     */
    public function getX()
    {
        return $this->x;
    }

    /**
     * Set lh
     *
     * @param integer $lh
     *
     * @return Yx
     */
    public function setLh($lh)
    {
        $this->lh = $lh;

        return $this;
    }

    /**
     * Get lh
     *
     * @return integer
     */
    public function getLh()
    {
        return $this->lh;
    }

    /**
     * Set rh
     *
     * @param integer $rh
     *
     * @return Yx
     */
    public function setRh($rh)
    {
        $this->rh = $rh;

        return $this;
    }

    /**
     * Get rh
     *
     * @return integer
     */
    public function getRh()
    {
        return $this->rh;
    }

    /**
     * Add fingeringOffline
     *
     * @param \AppBundle\Entity\FingeringOffline $fingeringOffline
     *
     * @return Yx
     */
    public function addFingeringOffline(\AppBundle\Entity\FingeringOffline $fingeringOffline)
    {
        $this->fingeringOffline[] = $fingeringOffline;

        return $this;
    }

    /**
     * Remove fingeringOffline
     *
     * @param \AppBundle\Entity\FingeringOffline $fingeringOffline
     */
    public function removeFingeringOffline(\AppBundle\Entity\FingeringOffline $fingeringOffline)
    {
        $this->fingeringOffline->removeElement($fingeringOffline);
    }

    /**
     * Get fingeringOffline
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFingeringOffline()
    {
        return $this->fingeringOffline;
    }

    /**
     * Set intervale
     *
     * @param \AppBundle\Entity\Intervale $intervale
     *
     * @return Yx
     */
    public function setIntervale(\AppBundle\Entity\Intervale $intervale = null)
    {
        $this->intervale = $intervale;

        return $this;
    }

    /**
     * Get intervale
     *
     * @return \AppBundle\Entity\Intervale
     */
    public function getIntervale()
    {
        return $this->intervale;
    }

    /**
     * Set westernSystem
     *
     * @param \AppBundle\Entity\WesternSystem $westernSystem
     *
     * @return Yx
     */
    public function setWesternSystem(\AppBundle\Entity\WesternSystem $westernSystem = null)
    {
        $this->westernSystem = $westernSystem;

        return $this;
    }

    /**
     * Get westernSystem
     *
     * @return \AppBundle\Entity\WesternSystem
     */
    public function getWesternSystem()
    {
        return $this->westernSystem;
    }

    /**
     * Set digitA
     *
     * @param integer $digitA
     *
     * @return Yx
     */
    public function setDigitA($digitA)
    {
        $this->digitA = $digitA;

        return $this;
    }

    /**
     * Get digitA
     *
     * @return integer
     */
    public function getDigitA()
    {
        return $this->digitA;
    }
}
