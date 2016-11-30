<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * RhythmMaterial
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\RhythmMaterialRepository")
 */
class RhythmMaterial
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
     * @ORM\Column(name="nominator", type="integer" , nullable=false)
     */
    private $nominator;

    /**
     * @var string
     *
     * @ORM\Column(name="denominator", type="integer" , nullable=false)
     */
    private $denominator;

    /**
     * @var string
     *
     * @ORM\Column(name="symbol", type="string" , nullable=true)
     */
    private $symbol;

    /**
     * @var string
     *
     * @ORM\Column(name="symbolSilence", type="string" , nullable=true)
     */
    private $symbolSilence;

    /**
     * @ORM\OneToMany(targetEntity="RhythmSequenceMaterials", mappedBy="rhythm_material", cascade={"persist", "remove"} )
     */
    protected $rhythm_sequence;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->rhythm_sequence = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return RhythmMaterial
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
     * Set nominator
     *
     * @param integer $nominator
     *
     * @return RhythmMaterial
     */
    public function setNominator($nominator)
    {
        $this->nominator = $nominator;

        return $this;
    }

    /**
     * Get nominator
     *
     * @return integer
     */
    public function getNominator()
    {
        return $this->nominator;
    }

    /**
     * Set denominator
     *
     * @param integer $denominator
     *
     * @return RhythmMaterial
     */
    public function setDenominator($denominator)
    {
        $this->denominator = $denominator;

        return $this;
    }

    /**
     * Get denominator
     *
     * @return integer
     */
    public function getDenominator()
    {
        return $this->denominator;
    }

    /**
     * Set symbol
     *
     * @param string $symbol
     *
     * @return RhythmMaterial
     */
    public function setSymbol($symbol)
    {
        $this->symbol = $symbol;

        return $this;
    }

    /**
     * Get symbol
     *
     * @return string
     */
    public function getSymbol()
    {
        return $this->symbol;
    }

    /**
     * Set symbolSilence
     *
     * @param string $symbolSilence
     *
     * @return RhythmMaterial
     */
    public function setSymbolSilence($symbolSilence)
    {
        $this->symbolSilence = $symbolSilence;

        return $this;
    }

    /**
     * Get symbolSilence
     *
     * @return string
     */
    public function getSymbolSilence()
    {
        return $this->symbolSilence;
    }

    /**
     * Add rhythmSequence
     *
     * @param \AppBundle\Entity\RhythmSequenceMaterials $rhythmSequence
     *
     * @return RhythmMaterial
     */
    public function addRhythmSequence(\AppBundle\Entity\RhythmSequenceMaterials $rhythmSequence)
    {
        $this->rhythm_sequence[] = $rhythmSequence;

        return $this;
    }

    /**
     * Remove rhythmSequence
     *
     * @param \AppBundle\Entity\RhythmSequenceMaterials $rhythmSequence
     */
    public function removeRhythmSequence(\AppBundle\Entity\RhythmSequenceMaterials $rhythmSequence)
    {
        $this->rhythm_sequence->removeElement($rhythmSequence);
    }

    /**
     * Get rhythmSequence
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRhythmSequence()
    {
        return $this->rhythm_sequence;
    }
}
