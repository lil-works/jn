<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * WesternSystem
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\WesternSystemRepository")
 */
class WesternSystem
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
     * @ORM\OneToMany(targetEntity="WesternSystem", mappedBy="root")
     */
    private $children;

    /**
     * @ORM\ManyToOne(targetEntity="WesternSystem", inversedBy="children")
     * @ORM\JoinColumn(name="root", referencedColumnName="id")
     */
    private $root;
    /**
     * @ORM\ManyToOne(targetEntity="Digit" ,  inversedBy="westernSystems")
     * @ORM\JoinColumn(name="digit", referencedColumnName="id")
     */
    private $digit;

    /**
     * @ORM\ManyToOne(targetEntity="Intervale")
     * @ORM\JoinColumn(name="intervale", referencedColumnName="id")
     */
    private $intervale;



    /**
     * @var string
     *
     * @ORM\Column(name="alteration", type="integer" , nullable=false)
     */
    private $alteration;
    /**
     * @var integer
     *
     * @ORM\Column(name="relativePosition", type="integer" , nullable=true)
     */
    private $relativePosition;


    public function __construct() {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return WesternSystem
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
     * Set alteration
     *
     * @param integer $alteration
     *
     * @return WesternSystem
     */
    public function setAlteration($alteration)
    {
        $this->alteration = $alteration;

        return $this;
    }

    /**
     * Get alteration
     *
     * @return integer
     */
    public function getAlteration()
    {
        return $this->alteration;
    }

    /**
     * Set root
     *
     * @param \AppBundle\Entity\WesternSystem $root
     *
     * @return WesternSystem
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
     * Add child
     *
     * @param \AppBundle\Entity\WesternSystem $child
     *
     * @return WesternSystem
     */
    public function addChild(\AppBundle\Entity\WesternSystem $child)
    {
        $this->children[] = $child;

        return $this;
    }

    /**
     * Remove child
     *
     * @param \AppBundle\Entity\WesternSystem $child
     */
    public function removeChild(\AppBundle\Entity\WesternSystem $child)
    {
        $this->children->removeElement($child);
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Set digit
     *
     * @param \AppBundle\Entity\Digit $digit
     *
     * @return WesternSystem
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
     * Set intervale
     *
     * @param \AppBundle\Entity\Intervale $intervale
     *
     * @return WesternSystem
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
     * Set relativePosition
     *
     * @param integer $relativePosition
     *
     * @return WesternSystem
     */
    public function setRelativePosition($relativePosition)
    {
        $this->relativePosition = $relativePosition;

        return $this;
    }

    /**
     * Get relativePosition
     *
     * @return integer
     */
    public function getRelativePosition()
    {
        return $this->relativePosition;
    }
}
