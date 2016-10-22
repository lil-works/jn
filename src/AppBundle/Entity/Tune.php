<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Tune
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\TuneRepository")
 */
class Tune
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
     * @ORM\OneToMany(targetEntity="TunesRealbooks", mappedBy="tune", cascade={"persist", "remove" }, orphanRemoval=TRUE)
     */
    protected $tunes_realbooks;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string",length=255)
     */
    private $title;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->realbooks = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set title
     *
     * @param string $title
     *
     * @return Tune
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Add tunesRealbook
     *
     * @param \AppBundle\Entity\TunesRealbooks $tunesRealbook
     *
     * @return Tune
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
}
