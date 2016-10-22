<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * instrument
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\InstrumentRepository")
 */
class Instrument
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
     * @var int
     *
     * @ORM\Column(name="caseMax", type="integer" , nullable=false)
     */
    private $caseMax;

    /**
     * @var string
     *
     * @ORM\Column(name="color", type="string" , nullable=true)
     */
    private $color;

    /**
     * @var integer
     *
     * @ORM\Column(name="icon", type="string",length=100)
     */
    private $icon;

    /**
     * @var boolean
     *
     * @ORM\Column(name="isDefault", type="boolean",nullable=true)
     */
    private $isDefault;

    /**
     * @ORM\OneToMany(targetEntity="InstrumentString", mappedBy="instrument", cascade={ "persist","remove"}, orphanRemoval=TRUE)
     */
    protected $strings;



    /**
     * Constructor
     */
    public function __construct()
    {
        $this->strings = new \Doctrine\Common\Collections\ArrayCollection();
    }


    public function getAbsolutePath()
    {
        return null === $this->icon
            ? null
            : $this->getUploadRootDir().'/'.$this->icon;
    }

    public function getWebPath()
    {
        return null === $this->icon
            ? null
            : $this->getUploadDir().'/'.$this->icon;
    }

    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__.'/../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return '/uploads/authors';
    }
    public function upload()
    {
        // the file property can be empty if the field is not required
        if (null === $this->getFile()) {
            return;
        }

        // use the original file name here but you should
        // sanitize it at least to avoid any security issues

        // move takes the target directory and then the
        // target filename to move to
        $this->getFile()->move(
            $this->getUploadRootDir(),
            $this->getFile()->getClientOriginalName()
        );

        // set the path property to the filename where you've saved the file
        $this->icon = $this->getFile()->getClientOriginalName();

        // clean up the file property as you won't need it anymore
        $this->file = null;
    }

    /**
     * @Assert\File(maxSize="6000000")
     */
    private $file;

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
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
     * @return Instrument
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
     * Set caseMax
     *
     * @param integer $caseMax
     *
     * @return Instrument
     */
    public function setCaseMax($caseMax)
    {
        $this->caseMax = $caseMax;

        return $this;
    }

    /**
     * Get caseMax
     *
     * @return integer
     */
    public function getCaseMax()
    {
        return $this->caseMax;
    }

    /**
     * Add string
     *
     * @param \AppBundle\Entity\InstrumentString $string
     *
     * @return Instrument
     */
    public function addString(\AppBundle\Entity\InstrumentString $string)
    {
        $this->strings[] = $string;

        return $this;
    }

    /**
     * Remove string
     *
     * @param \AppBundle\Entity\InstrumentString $string
     */
    public function removeString(\AppBundle\Entity\InstrumentString $string)
    {
        $this->strings->removeElement($string);
    }

    /**
     * Get strings
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getStrings()
    {
        return $this->strings;
    }

    /**
     * Set color
     *
     * @param string $color
     *
     * @return Instrument
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

    /**
     * Set icon
     *
     * @param string $icon
     *
     * @return Instrument
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * Get icon
     *
     * @return string
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * Set isDefault
     *
     * @param string $isDefault
     *
     * @return Instrument
     */
    public function setIsDefault($isDefault)
    {
        $this->isDefault = $isDefault;

        return $this;
    }

    /**
     * Get isDefault
     *
     * @return string
     */
    public function getIsDefault()
    {
        return $this->isDefault;
    }
}
