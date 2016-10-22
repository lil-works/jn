<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Realbook
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\AuthorRepository")
 */
class Realbook
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
     * @ORM\Column(name="firstPage", type="integer")
     */
    private $firstPage;

    /**
     * @var string
     *
     * @ORM\Column(name="indexName", type="string",length=255)
     */
    private $indexName;

    /**
     * @var string
     *
     * @ORM\Column(name="fileName", type="string",length=255,nullable=true)
     */
    private $fileName;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string",length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="imageType", type="string",length=5,nullable=true)
     */
    private $imageType;

    /**
     * @ORM\OneToMany(targetEntity="TunesRealbooks", mappedBy="realbook", cascade={"persist", "remove" }, orphanRemoval=TRUE)
     */
    private  $tunes_realbooks;


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
     * Set firstPage
     *
     * @param integer $firstPage
     *
     * @return Realbook
     */
    public function setFirstPage($firstPage)
    {
        $this->firstPage = $firstPage;

        return $this;
    }

    /**
     * Get firstPage
     *
     * @return integer
     */
    public function getFirstPage()
    {
        return $this->firstPage;
    }

    /**
     * Set indexName
     *
     * @param string $indexName
     *
     * @return Realbook
     */
    public function setIndexName($indexName)
    {
        $this->indexName = $indexName;

        return $this;
    }

    /**
     * Get indexName
     *
     * @return string
     */
    public function getIndexName()
    {
        return $this->indexName;
    }

    /**
     * Set fileName
     *
     * @param string $fileName
     *
     * @return Realbook
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;

        return $this;
    }

    /**
     * Get fileName
     *
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * Set imageType
     *
     * @param string $imageType
     *
     * @return Realbook
     */
    public function setImageType($imageType)
    {
        $this->imageType = $imageType;

        return $this;
    }

    /**
     * Get imageType
     *
     * @return string
     */
    public function getImageType()
    {
        return $this->imageType;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tunes = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add tune
     *
     * @param \AppBundle\Entity\TunesRealbooks $tune
     *
     * @return Realbook
     */
    public function addTune(\AppBundle\Entity\TunesRealbooks $tune)
    {
        $this->tunes[] = $tune;

        return $this;
    }

    /**
     * Remove tune
     *
     * @param \AppBundle\Entity\TunesRealbooks $tune
     */
    public function removeTune(\AppBundle\Entity\TunesRealbooks $tune)
    {
        $this->tunes->removeElement($tune);
    }

    /**
     * Get tunes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTunes()
    {
        return $this->tunes;
    }

    /**
     * Add tunesRealbook
     *
     * @param \AppBundle\Entity\TunesRealbooks $tunesRealbook
     *
     * @return Realbook
     */
    public function addTunesRealbook(\AppBundle\Entity\TunesRealbooks $tunesRealbook)
    {
        $this->tunes_realbooks[] = $tunesRealbook;

        return $this;
    }

    /**
     * Remove tunesRealbook
     *
     * @param \AppBundle\Entity\TunesRealbooks $tunesRealbook
     */
    public function removeTunesRealbook(\AppBundle\Entity\TunesRealbooks $tunesRealbook)
    {
        $this->tunes_realbooks->removeElement($tunesRealbook);
    }

    /**
     * Get tunesRealbooks
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTunesRealbooks()
    {
        return $this->tunes_realbooks;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Realbook
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
}
