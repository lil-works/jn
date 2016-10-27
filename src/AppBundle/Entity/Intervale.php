<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * intervale
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\IntervaleRepository")
 */
class Intervale
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
     * @var string
     *
     * @ORM\Column(name="name", type="string" , nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="roman", type="string" , nullable=true)
     */
    private $roman;

    /**
     * @var integer
     *
     * @ORM\Column(name="distance", type="integer" , nullable=true)
     */
    private $distance;

    /**
     * @var int
     *
     * @ORM\Column(name="delta", type="integer" , nullable=false)
     */
    private $delta;

    /**
     * @ORM\ManyToMany(targetEntity="Scale", mappedBy="intervales")
     */
    private $scales;

    /**
     * @var string
     *
     * @ORM\Column(name="color", type="string" , nullable=true)
     */
    private $color;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->scales = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set name
     *
     * @param string $name
     *
     * @return Intervale
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
     * Set delta
     *
     * @param integer $delta
     *
     * @return Intervale
     */
    public function setDelta($delta)
    {
        $this->delta = $delta;

        return $this;
    }

    /**
     * Get delta
     *
     * @return integer
     */
    public function getDelta()
    {
        return $this->delta;
    }

    /**
     * Add scale
     *
     * @param \AppBundle\Entity\Scale $scale
     *
     * @return Intervale
     */
    public function addScale(\AppBundle\Entity\Scale $scale)
    {
        $this->scales[] = $scale;

        return $this;
    }

    /**
     * Remove scale
     *
     * @param \AppBundle\Entity\Scale $scale
     */
    public function removeScale(\AppBundle\Entity\Scale $scale)
    {
        $this->scales->removeElement($scale);
    }

    /**
     * Get scales
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getScales()
    {
        return $this->scales;
    }

    /**
     * Set color
     *
     * @param string $color
     *
     * @return Intervale
     */
    public function setColor($color)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Get color
     *
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * Set roman
     *
     * @param string $roman
     *
     * @return Intervale
     */
    public function setRoman($roman)
    {
        $this->roman = $roman;

        return $this;
    }

    /**
     * Get roman
     *
     * @return string
     */
    public function getRoman()
    {
        return $this->roman;
    }

    /**
     * Set distance
     *
     * @param integer $distance
     *
     * @return Intervale
     */
    public function setDistance($distance)
    {
        $this->distance = $distance;

        return $this;
    }

    /**
     * Get distance
     *
     * @return integer
     */
    public function getDistance()
    {
        return $this->distance;
    }
}
