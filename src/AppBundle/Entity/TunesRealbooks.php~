<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * TuneRealbooks
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\TunesRealbooksRepository")
 */
class TunesRealbooks
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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Tune", inversedBy="tunes_realbooks")
     * */
    protected $tune;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Realbook", inversedBy="tunes_realbooks" , cascade={"persist"})
     * */
    protected $realbook;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="page", type="integer")
     */
    private $page;



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
     * Set page
     *
     * @param integer $page
     *
     * @return TunesRealbooks
     */
    public function setPage($page)
    {
        $this->page = $page;

        return $this;
    }

    /**
     * Get page
     *
     * @return integer
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Set tune
     *
     * @param \AppBundle\Entity\Tune $tune
     *
     * @return TunesRealbooks
     */
    public function setTune(\AppBundle\Entity\Tune $tune = null)
    {
        $this->tune = $tune;

        return $this;
    }

    /**
     * Get tune
     *
     * @return \AppBundle\Entity\Tune
     */
    public function getTune()
    {
        return $this->tune;
    }

    /**
     * Set realbook
     *
     * @param \AppBundle\Entity\Realbook $realbook
     *
     * @return TunesRealbooks
     */
    public function setRealbook(\AppBundle\Entity\Realbook $realbook = null)
    {
        $this->realbook = $realbook;

        return $this;
    }

    /**
     * Get realbook
     *
     * @return \AppBundle\Entity\Realbook
     */
    public function getRealbook()
    {
        return $this->realbook;
    }
}
