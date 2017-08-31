<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Company
 *
 * @ORM\Table(name="company")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CompanyRepository")
 */
class Company
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=255, nullable=true)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var int
     *
     * @ORM\Column(name="phone", type="integer")
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="photoavatar", type="string", length=255, nullable=true)
     */
    private $photoavatar;

    /**
     * @var string
     *
     * @ORM\Column(name="photoheader", type="string", length=255, nullable=true)
     */
    private $photoheader;

    /**
     * @var string
     *
     * @ORM\Column(name="titledescription", type="string", length=255, nullable=true)
     */
    private $titledescription;


    /**
     * Get id
     *
     * @return int
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
     * @return Company
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
     * Set address
     *
     * @param string $address
     *
     * @return Company
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Company
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
     * Set phone
     *
     * @param integer $phone
     *
     * @return Company
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return int
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set photoavatar
     *
     * @param string $photoavatar
     *
     * @return Company
     */
    public function setPhotoavatar($photoavatar)
    {
        $this->photoavatar = $photoavatar;

        return $this;
    }

    /**
     * Get photoavatar
     *
     * @return string
     */
    public function getPhotoavatar()
    {
        return $this->photoavatar;
    }

    /**
     * Set photoheader
     *
     * @param string $photoheader
     *
     * @return Company
     */
    public function setPhotoheader($photoheader)
    {
        $this->photoheader = $photoheader;

        return $this;
    }

    /**
     * Get photoheader
     *
     * @return string
     */
    public function getPhotoheader()
    {
        return $this->photoheader;
    }

    /**
     * Set titledescription
     *
     * @param string $titledescription
     *
     * @return Company
     */
    public function setTitledescription($titledescription)
    {
        $this->titledescription = $titledescription;

        return $this;
    }

    /**
     * Get titledescription
     *
     * @return string
     */
    public function getTitledescription()
    {
        return $this->titledescription;
    }

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="Company")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Company
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}