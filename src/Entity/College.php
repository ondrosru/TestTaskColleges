<?php

namespace App\Entity;

use App\Entity\Traits\CreatedAtTrait;
use App\Entity\Traits\UpdatedAtTrait;
use Doctrine\ORM\Mapping;

/**
 *
 * @Mapping\Table(name="college")
 * @Mapping\Entity(repositoryClass="App\Repository\CollegeRepository")
 * @Mapping\HasLifecycleCallbacks()
 *
 */
class College
{
    use CreatedAtTrait, UpdatedAtTrait;

    /**
     * @var int
     *
     * @Mapping\Column(name="id", type="integer", unique=true)
     * @Mapping\Id
     * @Mapping\GeneratedValue(strategy="IDENTITY")
     */
    private int $id;


    /**
     * @var string
     *
     * @Mapping\Column(name="name", type="string", nullable=false)
     */
    private string $name;

    /**
     * @var string|null
     *
     * @Mapping\Column(name="img_url", type="string", length=2048, nullable=true)
     */
    private ?string $imgUrl;

    /**
     * @var string
     *
     * @Mapping\Column(name="city", type="string", nullable=false)
     */
    private string $city;

    /**
     * @var string
     *
     * @Mapping\Column(name="state", type="string", nullable=false)
     */
    private string $state;

    /**
     * @var string|null
     *
     * @Mapping\Column(name="phone", type="string", nullable=true)
     */
    private ?string $phone;

    /**
     * @var string
     *
     * @Mapping\Column(name="address", type="string", nullable=false)
     */
    private string $address;

    /**
     * @var string|null
     *
     * @Mapping\Column(name="website_url", type="string", length=2048, nullable=true)
     */
    private ?string $website;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string|null
     */
    public function getImgUrl(): ?string
    {
        return $this->imgUrl;
    }

    /**
     * @param string|null $imgUrl
     */
    public function setImgUrl(?string $imgUrl): void
    {
        $this->imgUrl = $imgUrl;
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @param string $city
     */
    public function setCity(string $city): void
    {
        $this->city = $city;
    }

    /**
     * @return string
     */
    public function getState(): string
    {
        return $this->state;
    }

    /**
     * @param string $state
     */
    public function setState(string $state): void
    {
        $this->state = $state;
    }

    /**
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @param string|null $phone
     */
    public function setPhone(?string $phone): void
    {
        $this->phone = $phone;
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @param string $address
     */
    public function setAddress(string $address): void
    {
        $this->address = $address;
    }

    /**
     * @return string|null
     */
    public function getWebsite(): ?string
    {
        return $this->website;
    }

    /**
     * @param string|null $website
     */
    public function setWebsite(?string $website): void
    {
        $this->website = $website;
    }
}
