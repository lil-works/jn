<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
/**
 * sequence
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\SequenceRepository")
 * @UniqueEntity("name")
 */
class Sequence
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
     * @ORM\Column(name="name", type="string" , nullable=false, unique=true)
     */
    private $name;

    /**
     * @var text
     *
     * @ORM\Column(name="description", type="text" , nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="WesternSystem")
     * @ORM\JoinColumn(name="root", referencedColumnName="id")
     */
    private $root;

    /**
     * @ORM\ManyToMany(targetEntity="SequenceChanges", inversedBy="sequence")
     * @ORM\JoinTable(name="sequences_changes")
     * @ORM\OrderBy({"bar" = "ASC" })
     * @ORM\OrderBy({"beat" = "ASC" })
     */
    private $changes;


    /**
     * @var interger
     *
     * @ORM\Column(name="bars", type="integer" , nullable=false)
     */
    private $bars;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->changes = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Sequence
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
     * @return Sequence
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
     * Set root
     *
     * @param \AppBundle\Entity\WesternSystem $root
     *
     * @return Sequence
     */
    public function setRoot(\AppBundle\Entity\WesternSystem $root = null)
    {
        $this->root = $root;

        return $this;
    }

    /**
     * Get root
     *
     * @return \AppBundle\Entity\WesternSystem
     */
    public function getRoot()
    {
        return $this->root;
    }

    /**
     * Add change
     *
     * @param \AppBundle\Entity\SequenceChanges $change
     *
     * @return Sequence
     */
    public function addChange(\AppBundle\Entity\SequenceChanges $change)
    {
        $this->changes[] = $change;

        return $this;
    }

    /**
     * Remove change
     *
     * @param \AppBundle\Entity\SequenceChanges $change
     */
    public function removeChange(\AppBundle\Entity\SequenceChanges $change)
    {
        $this->changes->removeElement($change);
    }

    /**
     * Get changes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChanges()
    {
        return $this->changes;
    }

    /**
     * Set bars
     *
     * @param integer $bars
     *
     * @return Sequence
     */
    public function setBars($bars)
    {
        $this->bars = $bars;

        return $this;
    }

    /**
     * Get bars
     *
     * @return integer
     */
    public function getBars()
    {
        return $this->bars;
    }
}
