<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Traits\TimestampableTrait;
use App\Repository\ContactInfoRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ContactInfoRepository::class)]
#[ORM\HasLifecycleCallbacks]
class ContactInfo
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 200)]
    #[Assert\NotBlank]
    private ?string $displayName = null;

    #[ORM\Column(length: 200)]
    #[Assert\NotBlank]
    private ?string $headlineEn = null;

    #[ORM\Column(length: 200)]
    #[Assert\NotBlank]
    private ?string $headlineFr = null;

    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank]
    private ?string $summaryEn = null;

    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank]
    private ?string $summaryFr = null;

    #[ORM\Column(length: 180)]
    #[Assert\NotBlank]
    #[Assert\Email]
    private ?string $email = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $phone = null;

    #[ORM\Column(length: 200, nullable: true)]
    private ?string $locationEn = null;

    #[ORM\Column(length: 200, nullable: true)]
    private ?string $locationFr = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photoPath = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Url]
    private ?string $linkedinUrl = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Url]
    private ?string $githubUrl = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Url]
    private ?string $websiteUrl = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDisplayName(): ?string
    {
        return $this->displayName;
    }

    public function setDisplayName(string $displayName): self
    {
        $this->displayName = $displayName;

        return $this;
    }

    public function getHeadlineEn(): ?string
    {
        return $this->headlineEn;
    }

    public function setHeadlineEn(string $headlineEn): self
    {
        $this->headlineEn = $headlineEn;

        return $this;
    }

    public function getHeadlineFr(): ?string
    {
        return $this->headlineFr;
    }

    public function setHeadlineFr(string $headlineFr): self
    {
        $this->headlineFr = $headlineFr;

        return $this;
    }

    public function getSummaryEn(): ?string
    {
        return $this->summaryEn;
    }

    public function setSummaryEn(string $summaryEn): self
    {
        $this->summaryEn = $summaryEn;

        return $this;
    }

    public function getSummaryFr(): ?string
    {
        return $this->summaryFr;
    }

    public function setSummaryFr(string $summaryFr): self
    {
        $this->summaryFr = $summaryFr;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getLocationEn(): ?string
    {
        return $this->locationEn;
    }

    public function setLocationEn(?string $locationEn): self
    {
        $this->locationEn = $locationEn;

        return $this;
    }

    public function getLocationFr(): ?string
    {
        return $this->locationFr;
    }

    public function setLocationFr(?string $locationFr): self
    {
        $this->locationFr = $locationFr;

        return $this;
    }

    public function getPhotoPath(): ?string
    {
        return $this->photoPath;
    }

    public function setPhotoPath(?string $photoPath): self
    {
        $this->photoPath = $photoPath;

        return $this;
    }

    public function getLinkedinUrl(): ?string
    {
        return $this->linkedinUrl;
    }

    public function setLinkedinUrl(?string $linkedinUrl): self
    {
        $this->linkedinUrl = $linkedinUrl;

        return $this;
    }

    public function getGithubUrl(): ?string
    {
        return $this->githubUrl;
    }

    public function setGithubUrl(?string $githubUrl): self
    {
        $this->githubUrl = $githubUrl;

        return $this;
    }

    public function getWebsiteUrl(): ?string
    {
        return $this->websiteUrl;
    }

    public function setWebsiteUrl(?string $websiteUrl): self
    {
        $this->websiteUrl = $websiteUrl;

        return $this;
    }
}
