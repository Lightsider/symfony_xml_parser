<?php

namespace App\Entity;
use App\Service\PhoneHelper;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\DomCrawler\Crawler;

/**
 * @ORM\Entity
 * @ORM\Table(name="clients")
 */
class Client
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique = true)
     */
    private $client_xml_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $age;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $city;

    /**
     * @ORM\Column(type="datetime")
     */
    private $membership_date;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ClientPhone", mappedBy="client",orphanRemoval=true)
     */
    private $phones;

    public function __construct()
    {
        $this->phones = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClientXmlId(): ?string
    {
        return $this->client_xml_id;
    }

    public function setClientXmlId(string $client_xml_id): self
    {
        $this->client_xml_id = $client_xml_id;

        return $this;
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

    public function getAge(): ?string
    {
        return $this->age;
    }

    public function setAge(string $age): self
    {
        $this->age = $age;

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

    public function getMembershipDate(): ?\DateTimeInterface
    {
        return $this->membership_date;
    }

    public function setMembershipDate(\DateTimeInterface $membership_date): self
    {
        $this->membership_date = $membership_date;

        return $this;
    }

    /**
     * @return Collection|ClientPhone[]
     */
    public function getPhones(): Collection
    {
        return $this->phones;
    }

    /**
     * @return array
     */
    public function getPhonesClear() :array {
        $clear_phones = [];
        foreach ($this->phones as $phone) {
            $clear_phones[] = $phone->getPhone();
        }
        return $clear_phones;
    }

    public function addPhone(ClientPhone $phone): self
    {
        if (!$this->phones->contains($phone)) {
            $this->phones[] = $phone;
            $phone->setClient($this);
        }

        return $this;
    }

    public function removePhone(ClientPhone $phone)
    {
        $this->phones->removeElement($phone);
        $phone->setClient(null);
    }

    public function removeAllPhones() {
        $this->phones->clear();
    }
}