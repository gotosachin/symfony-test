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
 * @ORM\Table(name="people")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PeopleRepository")
 * @ORM\HasLifecycleCallbacks
 */
class People
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
     * @ORM\Column(name="name", type="string", length=100)
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(name="type", type="string", length=100)
     * @Assert\Choice(choices={"staff", "witness"}, message="Choose a valid type for a people.")
     */
    private $type;

    /**
     * @var Incident
     * @ORM\ManyToOne(targetEntity="Incident", cascade={"merge"}, fetch="EAGER")
     * @ORM\JoinColumn(name="incident_id", referencedColumnName="id")
     * @SWG\Property(property="incident", ref="#/definitions/Incident")
     * @Assert\Valid()
     */
    private $incident;

    /**
     * @var \DateTime
     * @ORM\Column(name="created_at", type="datetime")
     * @Serializer\Exclude()
     */
    private $createdAt;

    /**
     * @var \DateTime
     * @ORM\Column(name="updated_at", type="datetime")
     * @Serializer\Exclude()
     */
    private $updatedAt;

    /**
     * People constructor.
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
     * @param string $name
     *
     * @return $this
     */
    public function setName(string $name): People
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $type
     *
     * @return $this
     */
    public function setType(string $type): People
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param Incident $incident
     *
     * @return $this
     */
    public function setIncident(Incident $incident): People
    {
        $this->incident = $incident;

        return $this;
    }

    /**
     * @return Incident
     */
    public function getIncident(): Incident
    {
        return $this->incident;
    }

    /**
     * @param \DateTime $createdAt
     *
     * @return $this
     */
    public function setCreatedAt(\DateTime $createdAt): People
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
    public function setUpdatedAt(\DateTime $updatedAt): People
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
}
