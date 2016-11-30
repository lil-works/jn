<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
/**
 * sequenceChanges
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\SequenceChangesRepository")
 * @UniqueEntity({"bar","beat"})
 */
class SequenceChanges
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
     * @ORM\Column(name="bar", type="integer" , nullable=false)
     */
    private $bar;

    /**
     * @var integer
     *
     * @ORM\Column(name="beat", type="integer" , nullable=false)
     */
    private $beat;

    /**
     * @ORM\ManyToMany(targetEntity="Sequence", mappedBy="changes")
     */
    private $sequence;



    /**
     * @ORM\ManyToOne(targetEntity="Intervale")
     * @ORM\JoinColumn(name="intervale", referencedColumnName="id")
     */
    private $intervale;


    /**
     * @ORM\ManyToOne(targetEntity="Scale")
     * @ORM\JoinColumn(name="scale", referencedColumnName="id")
     */
    private $scale;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->sequence = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set bar
     *
     * @param integer $bar
     *
     * @return SequenceChanges
     */
    public function setBar($bar)
    {
        $this->bar = $bar;

        return $this;
    }

    /**
     * Get bar
     *
     * @return integer
     */
    public function getBar()
    {
        return $this->bar;
    }

    /**
     * Set beat
     *
     * @param integer $beat
     *
     * @return SequenceChanges
     */
    public function setBeat($beat)
    {
        $this->beat = $beat;

        return $this;
    }

    /**
     * Get beat
     *
     * @return integer
     */
    public function getBeat()
    {
        return $this->beat;
    }

    /**
     * Add sequence
     *
     * @param \AppBundle\Entity\Sequence $sequence
     *
     * @return SequenceChanges
     */
    public function addSequence(\AppBundle\Entity\Sequence $sequence)
    {
        $this->sequence[] = $sequence;

        return $this;
    }

    /**
     * Remove sequence
     *
     * @param \AppBundle\Entity\Sequence $sequence
     */
    public function removeSequence(\AppBundle\Entity\Sequence $sequence)
    {
        $this->sequence->removeElement($sequence);
    }

    /**
     * Get sequence
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSequence()
    {
        return $this->sequence;
    }

    /**
     * Set intervale
     *
     * @param \AppBundle\Entity\Intervale $intervale
     *
     * @return SequenceChanges
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
     * Set scale
     *
     * @param \AppBundle\Entity\Scale $scale
     *
     * @return SequenceChanges
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
}
