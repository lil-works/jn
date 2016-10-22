<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * fingering
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\FingeringRepository")
 */
class Fingering
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
     * @var description
     *
     * @ORM\Column(name="description", type="string" , nullable=false)
     */
    private $description;
    /**
     * @var float
     *
     * @ORM\Column(name="difficulty", type="float" , nullable=true)
     */
    private $difficulty;

    /**
     * @ORM\OneToMany(targetEntity="FingeringFinger", mappedBy="fingering", cascade={ "persist","remove"}, orphanRemoval=TRUE)
     */
    protected $fingers;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->fingers = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set difficulty
     *
     * @param float $difficulty
     *
     * @return Fingering
     */
    public function setDifficulty($difficulty)
    {
        $this->difficulty = $difficulty;

        return $this;
    }

    /**
     * Get difficulty
     *
     * @return float
     */
    public function getDifficulty()
    {
        return $this->difficulty;
    }

    /**
     * Add finger
     *
     * @param \AppBundle\Entity\FingeringFinger $finger
     *
     * @return Fingering
     */
    public function addFinger(\AppBundle\Entity\FingeringFinger $finger)
    {
        $this->fingers[] = $finger;

        return $this;
    }

    /**
     * Remove finger
     *
     * @param \AppBundle\Entity\FingeringFinger $finger
     */
    public function removeFinger(\AppBundle\Entity\FingeringFinger $finger)
    {
        $this->fingers->removeElement($finger);
    }

    /**
     * Get fingers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFingers()
    {
        return $this->fingers;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Fingering
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
}
