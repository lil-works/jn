<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * RhythmSequence
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\RhythmSequenceRepository")
 */
class RhythmSequence
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
     * @var integer
     *
     * @ORM\Column(name="signatureNominator", type="integer" , nullable=false)
     */
    private $signatureNominator;

    /**
     * @var integer
     *
     * @ORM\Column(name="signatureDenominator", type="integer" , nullable=false)
     */
    private $signatureDenominator;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string" , nullable=true , length=50)
     */
    private $name;


    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string" , nullable=true, length=255)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="RhythmSequenceMaterials", mappedBy="rhythm_sequence" ,cascade={"remove","persist"})
     */
    private $rhythm_sequence_materials;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->rhythm_sequence_materials = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set signatureNominator
     *
     * @param integer $signatureNominator
     *
     * @return RhythmSequence
     */
    public function setSignatureNominator($signatureNominator)
    {
        $this->signatureNominator = $signatureNominator;

        return $this;
    }

    /**
     * Get signatureNominator
     *
     * @return integer
     */
    public function getSignatureNominator()
    {
        return $this->signatureNominator;
    }

    /**
     * Set signatureDenominator
     *
     * @param integer $signatureDenominator
     *
     * @return RhythmSequence
     */
    public function setSignatureDenominator($signatureDenominator)
    {
        $this->signatureDenominator = $signatureDenominator;

        return $this;
    }

    /**
     * Get signatureDenominator
     *
     * @return integer
     */
    public function getSignatureDenominator()
    {
        return $this->signatureDenominator;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return RhythmSequence
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
     * Set description
     *
     * @param string $description
     *
     * @return RhythmSequence
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
     * Add rhythmSequenceMaterial
     *
     * @param \AppBundle\Entity\RhythmSequenceMaterials $rhythmSequenceMaterial
     *
     * @return RhythmSequence
     */
    public function addRhythmSequenceMaterial(\AppBundle\Entity\RhythmSequenceMaterials $rhythmSequenceMaterial)
    {
        $this->rhythm_sequence_materials[] = $rhythmSequenceMaterial;

        return $this;
    }

    /**
     * Remove rhythmSequenceMaterial
     *
     * @param \AppBundle\Entity\RhythmSequenceMaterials $rhythmSequenceMaterial
     */
    public function removeRhythmSequenceMaterial(\AppBundle\Entity\RhythmSequenceMaterials $rhythmSequenceMaterial)
    {
        $this->rhythm_sequence_materials->removeElement($rhythmSequenceMaterial);
    }

    /**
     * Get rhythmSequenceMaterials
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRhythmSequenceMaterials()
    {
        return $this->rhythm_sequence_materials;
    }
}
