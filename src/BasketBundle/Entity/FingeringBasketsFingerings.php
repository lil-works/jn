<?php
namespace BasketBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * FingeringBasketsFingerings
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="BasketBundle\Entity\FingeringBasketsFingeringsRepository")
 */
class FingeringBasketsFingerings
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
     * @ORM\Column(name="pos", type="integer" , nullable=true)
     */
    private $pos;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string" , nullable=true, unique=false)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string" , nullable=true, unique=false)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="FingeringBasket", inversedBy="fingeringbaskets_fingerings")
     * @ORM\JoinColumn(name="fingeringbasket", referencedColumnName="id", nullable=true)
     */
    protected $fingeringbasket;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\FingeringOffline", inversedBy="fingeringbaskets_fingerings")
     * @ORM\JoinColumn(name="fingeringOffline", referencedColumnName="id", nullable=true)
     */
    protected $fingeringOffline;



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
     * Set pos
     *
     * @param integer $pos
     *
     * @return FingeringBasketsFingerings
     */
    public function setPos($pos)
    {
        $this->pos = $pos;

        return $this;
    }

    /**
     * Get pos
     *
     * @return integer
     */
    public function getPos()
    {
        return $this->pos;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return FingeringBasketsFingerings
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
     * Set description
     *
     * @param string $description
     *
     * @return FingeringBasketsFingerings
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
     * Set fingeringbasket
     *
     * @param \BasketBundle\Entity\FingeringBasket $fingeringbasket
     *
     * @return FingeringBasketsFingerings
     */
    public function setFingeringbasket(\BasketBundle\Entity\FingeringBasket $fingeringbasket = null)
    {
        $this->fingeringbasket = $fingeringbasket;

        return $this;
    }

    /**
     * Get fingeringbasket
     *
     * @return \BasketBundle\Entity\FingeringBasket
     */
    public function getFingeringbasket()
    {
        return $this->fingeringbasket;
    }

    /**
     * Set fingeringOffline
     *
     * @param \AppBundle\Entity\FingeringOffline $fingeringOffline
     *
     * @return FingeringBasketsFingerings
     */
    public function setFingeringOffline(\AppBundle\Entity\FingeringOffline $fingeringOffline = null)
    {
        $this->fingeringOffline = $fingeringOffline;

        return $this;
    }

    /**
     * Get fingeringOffline
     *
     * @return \AppBundle\Entity\FingeringOffline
     */
    public function getFingeringOffline()
    {
        return $this->fingeringOffline;
    }
}
