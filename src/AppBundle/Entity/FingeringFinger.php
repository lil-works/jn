<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * fingeringfinger
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\FingeringFingerRepository")
 */
class FingeringFinger
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
     * @ORM\ManyToOne(targetEntity="Fingering", inversedBy="strings")
     * @ORM\JoinColumn(name="fingering", referencedColumnName="id", nullable=FALSE)
     */
    protected $fingering;



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
     * @return FingeringFinger
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
     * @return FingeringFinger
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
     * Set fingering
     *
     * @param \AppBundle\Entity\Fingering $fingering
     *
     * @return FingeringFinger
     */
    public function setFingering(\AppBundle\Entity\Fingering $fingering)
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
}
