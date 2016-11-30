<?php
// src/AppBundle/Entity/User.php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;




    /**
     * @ORM\OneToMany(targetEntity="BasketBundle\Entity\FingeringBasket", mappedBy="createdBy")
     */
    private $fingeringbaskets;

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return User
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
     * Add fingeringbasket
     *
     * @param \BasketBundle\Entity\FingeringBasket $fingeringbasket
     *
     * @return User
     */
    public function addFingeringbasket(\BasketBundle\Entity\FingeringBasket $fingeringbasket)
    {
        $this->fingeringbaskets[] = $fingeringbasket;

        return $this;
    }

    /**
     * Remove fingeringbasket
     *
     * @param \BasketBundle\Entity\FingeringBasket $fingeringbasket
     */
    public function removeFingeringbasket(\BasketBundle\Entity\FingeringBasket $fingeringbasket)
    {
        $this->fingeringbaskets->removeElement($fingeringbasket);
    }

    /**
     * Get fingeringbaskets
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFingeringbaskets()
    {
        return $this->fingeringbaskets;
    }
}
