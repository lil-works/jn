<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * digit
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\DigitRepository")
 */
class Digit
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
     * @var float
     *
     * @ORM\Column(name="frequency0", type="float" , nullable=false)
     */
    private $frequency0;

    /**
     * @var int
     *
     * @ORM\Column(name="value", type="integer" , nullable=false)
     */
    private $value;
    /**
     * @var string
     *
     * @ORM\Column(name="infoTone", type="string" , nullable=true)
     */
    private $infoTone;

    /**
     * @var string
     *
     * @ORM\Column(name="color", type="string" , nullable=true)
     */
    private $color;

    /**
     * @ORM\OneToMany(targetEntity="WesternSystem", mappedBy="digit", cascade={ "persist","remove"}, orphanRemoval=TRUE)
     */
    protected $westernSystems;

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
     * Set frequency0
     *
     * @param float $frequency0
     *
     * @return Digit
     */
    public function setFrequency0($frequency0)
    {
        $this->frequency0 = $frequency0;

        return $this;
    }

    /**
     * Get frequency0
     *
     * @return float
     */
    public function getFrequency0()
    {
        return $this->frequency0;
    }

    /**
     * Set value
     *
     * @param integer $value
     *
     * @return Digit
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return integer
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set infoTone
     *
     * @param integer $infoTone
     *
     * @return Digit
     */
    public function setInfoTone($infoTone)
    {
        $this->infoTone = $infoTone;

        return $this;
    }

    /**
     * Get infoTone
     *
     * @return integer
     */
    public function getInfoTone()
    {
        return $this->infoTone;
    }

    /**
     * Set color
     *
     * @param string $color
     *
     * @return Digit
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
     * Constructor
     */
    public function __construct()
    {
        $this->westernSystems = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add westernSystem
     *
     * @param \AppBundle\Entity\WesternSystem $westernSystem
     *
     * @return Digit
     */
    public function addWesternSystem(\AppBundle\Entity\WesternSystem $westernSystem)
    {
        $this->westernSystems[] = $westernSystem;

        return $this;
    }

    /**
     * Remove westernSystem
     *
     * @param \AppBundle\Entity\WesternSystem $westernSystem
     */
    public function removeWesternSystem(\AppBundle\Entity\WesternSystem $westernSystem)
    {
        $this->westernSystems->removeElement($westernSystem);
    }

    /**
     * Get westernSystems
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getWesternSystems()
    {
        return $this->westernSystems;
    }
}
