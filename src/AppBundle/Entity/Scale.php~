<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
/**
 * scale
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\ScaleRepository")
 * @UniqueEntity("name")
 */
class Scale
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
     * @ORM\ManyToMany(targetEntity="Descriptor", inversedBy="scales")
     * @ORM\JoinTable(name="scales_descriptors")
     */
    private $descriptors;


    /**
     * @ORM\ManyToMany(targetEntity="Intervale", inversedBy="scales")
     * @ORM\JoinTable(name="scales_intervales")
     * @ORM\OrderBy({"delta" = "ASC"})
     */
    private $intervales;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->intervales = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Scale
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
     * Add intervale
     *
     * @param \AppBundle\Entity\Intervale $intervale
     *
     * @return Scale
     */
    public function addIntervale(\AppBundle\Entity\Intervale $intervale)
    {
        $this->intervales[] = $intervale;

        return $this;
    }

    /**
     * Remove intervale
     *
     * @param \AppBundle\Entity\Intervale $intervale
     */
    public function removeIntervale(\AppBundle\Entity\Intervale $intervale)
    {
        $this->intervales->removeElement($intervale);
    }

    /**
     * Get intervales
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getIntervales()
    {
        return $this->intervales;
    }

    /**
     * Add descriptor
     *
     * @param \AppBundle\Entity\Descriptor $descriptor
     *
     * @return Scale
     */
    public function addDescriptor(\AppBundle\Entity\Descriptor $descriptor)
    {
        $this->descriptors[] = $descriptor;

        return $this;
    }

    /**
     * Remove descriptor
     *
     * @param \AppBundle\Entity\Descriptor $descriptor
     */
    public function removeDescriptor(\AppBundle\Entity\Descriptor $descriptor)
    {
        $this->descriptors->removeElement($descriptor);
    }

    /**
     * Get descriptors
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDescriptors()
    {
        return $this->descriptors;
    }
}
