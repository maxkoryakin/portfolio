<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Traits\TimestampableTrait;
use App\Repository\WorkExperienceRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: WorkExperienceRepository::class)]
#[ORM\HasLifecycleCallbacks]
class WorkExperience
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 200)]
    #[Assert\NotBlank]
    private ?string $companyEn = null;

    #[ORM\Column(length: 200)]
    #[Assert\NotBlank]
    private ?string $companyFr = null;

    #[ORM\Column(length: 200)]
    #[Assert\NotBlank]
    private ?string $roleEn = null;

    #[ORM\Column(length: 200)]
    #[Assert\NotBlank]
    private ?string $roleFr = null;

    #[ORM\Column(length: 200, nullable: true)]
    private ?string $locationEn = null;

    #[ORM\Column(length: 200, nullable: true)]
    private ?string $locationFr = null;

    #[ORM\Column(type: 'date_immutable')]
    #[Assert\NotBlank]
    private ?\DateTimeImmutable $startDate = null;

    #[ORM\Column(type: 'date_immutable', nullable: true)]
    private ?\DateTimeImmutable $endDate = null;

    #[ORM\Column(options: ['default' => false])]
    private bool $isCurrent = false;

    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank]
    private ?string $descriptionEn = null;

    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank]
    private ?string $descriptionFr = null;

    #[ORM\Column(options: ['default' => 0])]
    private int $sortOrder = 0;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCompanyEn(): ?string
    {
        return $this->companyEn;
    }

    public function setCompanyEn(string $companyEn): self
    {
        $this->companyEn = $companyEn;

        return $this;
    }

    public function getCompanyFr(): ?string
    {
        return $this->companyFr;
    }

    public function setCompanyFr(string $companyFr): self
    {
        $this->companyFr = $companyFr;

        return $this;
    }

    public function getRoleEn(): ?string
    {
        return $this->roleEn;
    }

    public function setRoleEn(string $roleEn): self
    {
        $this->roleEn = $roleEn;

        return $this;
    }

    public function getRoleFr(): ?string
    {
        return $this->roleFr;
    }

    public function setRoleFr(string $roleFr): self
    {
        $this->roleFr = $roleFr;

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

    public function getStartDate(): ?\DateTimeImmutable
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeImmutable $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeImmutable
    {
        return $this->endDate;
    }

    public function setEndDate(?\DateTimeImmutable $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function isCurrent(): bool
    {
        return $this->isCurrent;
    }

    public function setIsCurrent(bool $isCurrent): self
    {
        $this->isCurrent = $isCurrent;

        return $this;
    }

    public function getDescriptionEn(): ?string
    {
        return $this->descriptionEn;
    }

    public function setDescriptionEn(string $descriptionEn): self
    {
        $this->descriptionEn = $descriptionEn;

        return $this;
    }

    public function getDescriptionFr(): ?string
    {
        return $this->descriptionFr;
    }

    public function setDescriptionFr(string $descriptionFr): self
    {
        $this->descriptionFr = $descriptionFr;

        return $this;
    }

    public function getSortOrder(): int
    {
        return $this->sortOrder;
    }

    public function setSortOrder(int $sortOrder): self
    {
        $this->sortOrder = $sortOrder;

        return $this;
    }
}
