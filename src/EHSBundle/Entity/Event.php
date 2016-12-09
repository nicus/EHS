<?php

namespace EHSBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Event
 *
 * @ORM\Table(name="event")
 * @ORM\Entity(repositoryClass="EHSBundle\Repository\EventRepository")
 */
class Event
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
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="presentation", type="text")
     */
    private $presentation;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start_date", type="datetime")
     */
    private $startDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="end_date", type="datetime")
     */
    private $endDate;

    /**
     * @var int
     *
     * @ORM\Column(name="place_number", type="integer", nullable=true)
     */
    private $placeNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="add_info", type="text", nullable=true)
     */
    private $addInfo;

    /**
     * @var bool
     *
     * @ORM\Column(name="archived", type="boolean")
     */
    private $archived;

    /**
     * event images
     *
     * @ORM\ManyToMany(targetEntity="EHSBundle\Entity\Image", inversedBy="events")
     * @ORM\JoinTable(name="event_img")
     */
    private $images;

    /**
     * event tags
     *
     * @ORM\ManyToMany(targetEntity="EHSBundle\Entity\Tag")
     * @ORM\JoinTable(name="event_tag",
     *     joinColumns={@ORM\JoinColumn(name="event_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="tag_id", referencedColumnName="id")})
     */
    private $tags;

    /**
     * inscriptions event
     *
     * @ORM\ManyToMany(targetEntity="EHSBundle\Entity\EventInscription")
     * @ORM\JoinTable(name="participate",
     *     joinColumns={@ORM\JoinColumn(name="event_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="inscription_id", referencedColumnName="id")})
     */
    private $inscriptions;


    /**
     * appointment event
     *
     * @ORM\ManyToOne(targetEntity="EHSBundle\Entity\Appointment")
     * @ORM\JoinColumn(name="appointment_id", referencedColumnName="id")
     */
    private $appointment;


    /**
     * Event constructor.
     */
    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->tags= new ArrayCollection();
        $this->inscriptions= new ArrayCollection();
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
     * @return Event
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
     * Set presentation
     *
     * @param string $presentation
     * @return Event
     */
    public function setPresentation($presentation)
    {
        $this->presentation = $presentation;

        return $this;
    }

    /**
     * Get presentation
     *
     * @return string 
     */
    public function getPresentation()
    {
        return $this->presentation;
    }

    /**
     * Set startDate
     *
     * @param \DateTime $startDate
     * @return Event
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime 
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set endDate
     *
     * @param \DateTime $endDate
     * @return Event
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Get endDate
     *
     * @return \DateTime 
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Set placeNumber
     *
     * @param integer $placeNumber
     * @return Event
     */
    public function setPlaceNumber($placeNumber)
    {
        $this->placeNumber = $placeNumber;

        return $this;
    }

    /**
     * Get placeNumber
     *
     * @return integer 
     */
    public function getPlaceNumber()
    {
        return $this->placeNumber;
    }

    /**
     * Set addInfo
     *
     * @param string $addInfo
     * @return Event
     */
    public function setAddInfo($addInfo)
    {
        $this->addInfo = $addInfo;

        return $this;
    }

    /**
     * Get addInfo
     *
     * @return string 
     */
    public function getAddInfo()
    {
        return $this->addInfo;
    }

    /**
     * Set archived
     *
     * @param boolean $archived
     * @return Event
     */
    public function setArchived($archived)
    {
        $this->archived = $archived;

        return $this;
    }

    /**
     * Get archived
     *
     * @return boolean 
     */
    public function getArchived()
    {
        return $this->archived;
    }

    /**
     * @return mixed
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * @param mixed $images
     */
    public function setImages($images)
    {
        $this->images = $images;
    }

    /**
     * @return mixed
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param mixed $tags
     */
    public function setTags($tags)
    {
        $this->tags = $tags;
    }

    /**
     * @return mixed
     */
    public function getInscriptions()
    {
        return $this->inscriptions;
    }

    /**
     * @param mixed $inscriptions
     */
    public function setInscriptions($inscriptions)
    {
        $this->inscriptions = $inscriptions;
    }

    /**
     * @return mixed
     */
    public function getAppointment()
    {
        return $this->appointment;
    }

    /**
     * @param mixed $appointment
     */
    public function setAppointment($appointment)
    {
        $this->appointment = $appointment;
    }

}
