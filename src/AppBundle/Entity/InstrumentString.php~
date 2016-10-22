<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * instrument
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\InstrumentStringRepository")
 */
class InstrumentString
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
     * @ORM\ManyToOne(targetEntity="Digit")
     * @ORM\JoinColumn(name="digit", referencedColumnName="id")
     */
    private $digit;

    /**
     * @var int
     *
     * @ORM\Column(name="octave", type="integer" , nullable=false)
     */
    private $octave;

    /**
     * @var int
     *
     * @ORM\Column(name="pos", type="integer" , nullable=false)
     */
    private $pos;

    /**
     * @ORM\ManyToOne(targetEntity="Instrument", inversedBy="strings")
     * @ORM\JoinColumn(name="instrument", referencedColumnName="id", nullable=FALSE)
     */
    protected $instrument;




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
     * Set octave
     *
     * @param integer $octave
     *
     * @return InstrumentString
     */
    public function setOctave($octave)
    {
        $this->octave = $octave;

        return $this;
    }

    /**
     * Get octave
     *
     * @return integer
     */
    public function getOctave()
    {
        return $this->octave;
    }

    /**
     * Set pos
     *
     * @param integer $pos
     *
     * @return InstrumentString
     */
    public function setPos($pos)
    {
        $this->pos = $pos;

        return $this;
    }

    /**
     * Get pos
     *
     * @return integer
     */
    public function getPos()
    {
        return $this->pos;
    }

    /**
     * Set digit
     *
     * @param \AppBundle\Entity\Digit $digit
     *
     * @return InstrumentString
     */
    public function setDigit(\AppBundle\Entity\Digit $digit = null)
    {
        $this->digit = $digit;

        return $this;
    }

    /**
     * Get digit
     *
     * @return \AppBundle\Entity\Digit
     */
    public function getDigit()
    {
        return $this->digit;
    }

    /**
     * Set instrument
     *
     * @param \AppBundle\Entity\Instrument $instrument
     *
     * @return InstrumentString
     */
    public function setInstrument(\AppBundle\Entity\Instrument $instrument)
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
}
