<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Available
 *
 * @ORM\Table(name="available")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AvailableRepository")
 */
class Available
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
     * @ORM\Column(name="day", type="string", length=255)
     */
    private $day;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="startAm", type="time")
     */
    private $startAm;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="endAm", type="time")
     */
    private $endAm;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="startPm", type="time")
     */
    private $startPm;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="endPm", type="time")
     */
    private $endPm;


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
     * Set day
     *
     * @param string $day
     *
     * @return Available
     */
    public function setDay($day)
    {
        $this->day = $day;

        return $this;
    }

    /**
     * Get day
     *
     * @return string
     */
    public function getDay()
    {
        return $this->day;
    }

    /**
     * Set startAm
     *
     * @param \DateTime $startAm
     *
     * @return Available
     */
    public function setStartAm($startAm)
    {
        $this->startAm = $startAm;

        return $this;
    }

    /**
     * Get startAm
     *
     * @return \DateTime
     */
    public function getStartAm()
    {
        return $this->startAm;
    }

    /**
     * Set endAm
     *
     * @param \DateTime $endAm
     *
     * @return Available
     */
    public function setEndAm($endAm)
    {
        $this->endAm = $endAm;

        return $this;
    }

    /**
     * Get endAm
     *
     * @return \DateTime
     */
    public function getEndAm()
    {
        return $this->endAm;
    }

    /**
     * Set startPm
     *
     * @param \DateTime $startPm
     *
     * @return Available
     */
    public function setStartPm($startPm)
    {
        $this->startPm = $startPm;

        return $this;
    }

    /**
     * Get startPm
     *
     * @return \DateTime
     */
    public function getStartPm()
    {
        return $this->startPm;
    }

    /**
     * Set endPm
     *
     * @param \DateTime $endPm
     *
     * @return Available
     */
    public function setEndPm($endPm)
    {
        $this->endPm = $endPm;

        return $this;
    }

    /**
     * Get endPm
     *
     * @return \DateTime
     */
    public function getEndPm()
    {
        return $this->endPm;
    }

    /**
     * @ORM\ManyToOne(targetEntity="Options", inversedBy="available")
     * @ORM\JoinColumn(name="options_id", referencedColumnName="id")
     */
    private $options;

    /**
     * Set options
     *
     * @param \AppBundle\Entity\Options $options
     *
     * @return Available
     */
    public function setOptions(\AppBundle\Entity\Options $options = null)
    {
        $this->options = $options;

        return $this;
    }

    /**
     * Get options
     *
     * @return \AppBundle\Entity\Options
     */
    public function getOptions()
    {
        return $this->options;
    }
}
