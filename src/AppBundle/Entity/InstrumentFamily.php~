<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * instrumentFamily
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\InstrumentFamilyRepository")
 */
class InstrumentFamily
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
     * @ORM\Column(name="icon", type="string",length=50,nullable=true)
     */
    private $icon;

    /**
     * @ORM\OneToMany(targetEntity="Instrument", mappedBy="family")
     */
    private $instruments;




    /**
     * Constructor
     */
    public function __construct()
    {
        $this->strings = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set icon
     *
     * @param string $icon
     *
     * @return Instrument
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * Get icon
     *
     * @return string
     */
    public function getIcon()
    {
        return $this->icon;
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
     * @return InstrumentFamily
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
     * Add instrument
     *
     * @param \AppBundle\Entity\Instrument $instrument
     *
     * @return InstrumentFamily
     */
    public function addInstrument(\AppBundle\Entity\Instrument $instrument)
    {
        $this->instruments[] = $instrument;

        return $this;
    }

    /**
     * Remove instrument
     *
     * @param \AppBundle\Entity\Instrument $instrument
     */
    public function removeInstrument(\AppBundle\Entity\Instrument $instrument)
    {
        $this->instruments->removeElement($instrument);
    }

    /**
     * Get instruments
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getInstruments()
    {
        return $this->instruments;
    }
}
