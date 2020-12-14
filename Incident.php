<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Swagger\Annotations as SWG;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @package AppBundle\Entity
 * @ORM\Table(name="incident")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\IncidentRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Incident
{
    /**
     * @var int
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="title", type="string", length=100)
     */
    private $title;

    /**
     * @var string
     * @ORM\Column(name="comments", type="string", length=255)
     */
    private $comments;

    /**
     * @var Category
     * @ORM\ManyToOne(targetEntity="Category",cascade={"merge"})
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     * @SWG\Property(property="category", ref="#/definitions/Category")
     */
    private $category;

    /**
     * @var Location
     * @ORM\ManyToOne(targetEntity="Location",cascade={"merge"})
     * @ORM\JoinColumn(name="location_id", referencedColumnName="id")
     * @SWG\Property(property="location", ref="#/definitions/Location")
     */
    private $location;

    /**
     * @var \DateTime
     * @ORM\Column(name="incident_date", type="datetime")
     * @SWG\Property()
     * @Assert\NotBlank(message="Incident date is required!!")
     */
    private $incidentDate;

    /**
     * @var null|Collection
     * @ORM\OneToMany(targetEntity="People", mappedBy="incident", fetch="EAGER", cascade={"remove"})
     * @ORM\JoinColumn(name="incident_id", referencedColumnName="id")
     * @SWG\Property(property="incident", ref="#/definitions/Incident")
     * @ORM\OrderBy({"id" = "DESC"})
     */
    private $people;

    /**
     * @var \DateTime
     * @ORM\Column(name="created_at", type="datetime")
     * @Assert\DateTime()
     * @Serializer\Exclude()
     */
    private $createdAt;

    /**
     * @var \DateTime
     * @ORM\Column(name="updated_at", type="datetime")
     * @Assert\DateTime()
     * @Serializer\Exclude()
     */
    private $updatedAt;

    /**
     * Incident constructor.
     */
    public function __construct()
    {
        // Defaults
        $this->setCreatedAt(new \DateTime());
        $this->setUpdatedAt(new \DateTime());
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param string $title
     *
     * @return $this
     */
    public function setTitle(string $title): Incident
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param $comments
     *
     * @return $this
     */
    public function setComments($comments): Incident
    {
        $this->comments = $comments;

        return $this;
    }

    /**
     * @return string
     */
    public function getComments(): string
    {
        return $this->comments;
    }

    /**
     * @param Category $category
     *
     * @return $this
     */
    public function setCategory(Category $category): Incident
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Category
     */
    public function getCategory(): Category
    {
        return $this->category;
    }

    /**
     * @param Location $location
     *
     * @return $this
     */
    public function setLocation(Location $location): Incident
    {
        $this->location = $location;

        return $this;
    }

    /**
     * @return Location
     */
    public function getLocation(): Location
    {
        return $this->location;
    }

    /**
     * @param \DateTime $incidentDate
     *
     * @return $this
     */
    public function setIncidentDate(\DateTime $incidentDate): Incident
    {
        $this->incidentDate = $incidentDate;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getIncidentDate(): \DateTime
    {
        return $this->incidentDate;
    }

    /**
     * @param \DateTime $createdAt
     *
     * @return $this
     */
    public function setCreatedAt(\DateTime $createdAt): Incident
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $updatedAt
     *
     * @return $this
     */
    public function setUpdatedAt(\DateTime $updatedAt): Incident
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @return null|Collection
     */
    public function getPeople(): ?Collection
    {
        return $this->people;
    }
}
