<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * RhythmSequenceMaterials
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\RhythmSequenceMaterialsRepository")
 */
class RhythmSequenceMaterials
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
     * @ORM\Column(name="integer", type="integer" , nullable=false)
     */
    private $pos;

    /**
     * @var string
     *
     * @ORM\Column(name="isSilent", type="boolean" , nullable=true)
     */
    private $isSilent;

    /**
     * @var string
     *
     * @ORM\Column(name="isPointed", type="boolean" , nullable=true)
     */
    private $isPointed;

    /**
     * @ORM\ManyToOne(targetEntity="RhythmSequence", inversedBy="rhythm_sequence_materials")
     * @ORM\JoinColumn(name="rhythm_sequence", referencedColumnName="id", nullable=FALSE)
     */
    protected $rhythm_sequence;

    /**
     * @ORM\ManyToOne(targetEntity="RhythmMaterial", inversedBy="rhythm_sequence_materials")
     * @ORM\JoinColumn(name="rhythm_material", referencedColumnName="id", nullable=FALSE)
     */
    protected $rhythm_material;



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
     * Set pos
     *
     * @param \pos $pos
     *
     * @return RhythmSequenceMaterials
     */
    public function setPos(\pos $pos)
    {
        $this->pos = $pos;

        return $this;
    }

    /**
     * Get pos
     *
     * @return \pos
     */
    public function getPos()
    {
        return $this->pos;
    }

    /**
     * Set isSilent
     *
     * @param boolean $isSilent
     *
     * @return RhythmSequenceMaterials
     */
    public function setIsSilent($isSilent)
    {
        $this->isSilent = $isSilent;

        return $this;
    }

    /**
     * Get isSilent
     *
     * @return boolean
     */
    public function getIsSilent()
    {
        return $this->isSilent;
    }

    /**
     * Set isPointed
     *
     * @param boolean $isPointed
     *
     * @return RhythmSequenceMaterials
     */
    public function setIsPointed($isPointed)
    {
        $this->isPointed = $isPointed;

        return $this;
    }

    /**
     * Get isPointed
     *
     * @return boolean
     */
    public function getIsPointed()
    {
        return $this->isPointed;
    }

    /**
     * Set rhythmSequence
     *
     * @param \AppBundle\Entity\RhythmSequence $rhythmSequence
     *
     * @return RhythmSequenceMaterials
     */
    public function setRhythmSequence(\AppBundle\Entity\RhythmSequence $rhythmSequence)
    {
        $this->rhythm_sequence = $rhythmSequence;

        return $this;
    }

    /**
     * Get rhythmSequence
     *
     * @return \AppBundle\Entity\RhythmSequence
     */
    public function getRhythmSequence()
    {
        return $this->rhythm_sequence;
    }

    /**
     * Set rhythmMaterial
     *
     * @param \AppBundle\Entity\RhythmMaterial $rhythmMaterial
     *
     * @return RhythmSequenceMaterials
     */
    public function setRhythmMaterial(\AppBundle\Entity\RhythmMaterial $rhythmMaterial)
    {
        $this->rhythm_material = $rhythmMaterial;

        return $this;
    }

    /**
     * Get rhythmMaterial
     *
     * @return \AppBundle\Entity\RhythmMaterial
     */
    public function getRhythmMaterial()
    {
        return $this->rhythm_material;
    }
}
