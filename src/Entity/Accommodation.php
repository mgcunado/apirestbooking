<?php

namespace App\Entity;

use App\Repository\AccommodationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=AccommodationRepository::class)
 *
 * @Assert\Expression(
 *     "this.getBeds() >= this.getMaxGuests()",
 *     message="MaxGuests cannot exceed the number of beds!"
 * )
 */
class Accommodation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=150)
     * @Assert\Length(max=150)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, options={"default"="some direction"})
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=100, options={"default"="Zaragoza"})
     */
    private $city;

    /**
     * @ORM\Column(type="integer", options={"default"=50005})
     */
    private $postalCode;

    /**
     * @ORM\Column(type="string", length=2, options={"default"="ES"})
     */
    private $country;

    /**
     * @ORM\Column(type="string", length=10)
     * @Assert\Choice({"HOUSE", "FLAT", "VILLA"})
     */
    private $type;

    /**
     * @ORM\Column(type="integer")
     */
    private $maxGuests;

    /**
     * @ORM\Column(type="datetime")
     */
    private $lastUpdate;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="accommodations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="integer")
     * @Assert\GreaterThanOrEqual(1)
     */
    private $livingRooms;

    /**
     * @ORM\Column(type="integer")
     * @Assert\GreaterThanOrEqual(1)
     */
    private $bedrooms;

    /**
     * @ORM\Column(type="integer")
     * @Assert\GreaterThanOrEqual(1)
     */
    private $beds;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getPostalCode(): ?int
    {
        return $this->postalCode;
    }

    public function setPostalCode(int $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getMaxGuests(): ?int
    {
        return $this->maxGuests;
    }

    public function setMaxGuests(int $maxGuests): self
    {
        $this->maxGuests = $maxGuests;

        return $this;
    }

    public function getLastUpdate(): ?\DateTimeInterface
    {
        return $this->lastUpdate;
    }

    public function setLastUpdate(\DateTimeInterface $lastUpdate): self
    {
        $this->lastUpdate = $lastUpdate;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getLivingRooms(): ?int
    {
        return $this->livingRooms;
    }

    public function setLivingRooms(int $livingRooms): self
    {
        $this->livingRooms = $livingRooms;

        return $this;
    }

    public function getBedrooms(): ?int
    {
        return $this->bedrooms;
    }

    public function setBedrooms(int $bedrooms): self
    {
        $this->bedrooms = $bedrooms;

        return $this;
    }

    public function getBeds(): ?int
    {
        return $this->beds;
    }

    public function setBeds(int $beds): self
    {
        $this->beds = $beds;

        return $this;
    }

    public function __construct()
    {
        $this->lastUpdate = new \DateTime();
        $this->address = 'some direction';
        $this->city = 'Zaragoza';
        $this->postalCode = '50005';
        $this->country = 'ES';
    }

    /* public function __toString() */
    /* { */
    /*     return (string)$this->getLastUpdate(); */
    /* } */
}
