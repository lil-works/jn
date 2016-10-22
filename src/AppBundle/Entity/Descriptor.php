<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * descriptor
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\DescriptorRepository")
 */
class Descriptor
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
     * @ORM\Column(name="color", type="string" , length=6 , nullable=true)
     */
    private $color;
    /**
     * @ORM\ManyToMany(targetEntity="Scale", mappedBy="descriptors")
     */
    private $scales;


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
     * @return Descriptor
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
     * Constructor
     */
    public function __construct()
    {
        $this->scales = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add scale
     *
     * @param \AppBundle\Entity\Scale $scale
     *
     * @return Descriptor
     */
    public function addScale(\AppBundle\Entity\Scale $scale)
    {
        $this->scales[] = $scale;

        return $this;
    }

    /**
     * Remove scale
     *
     * @param \AppBundle\Entity\Scale $scale
     */
    public function removeScale(\AppBundle\Entity\Scale $scale)
    {
        $this->scales->removeElement($scale);
    }

    /**
     * Get scales
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getScales()
    {
        return $this->scales;
    }

    /**
     * Set color
     *
     * @param string $color
     *
     * @return Descriptor
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
}
