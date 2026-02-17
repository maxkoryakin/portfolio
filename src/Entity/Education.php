<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Traits\TimestampableTrait;
use App\Repository\EducationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EducationRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Education
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 200)]
    #[Assert\NotBlank]
    private ?string $institutionEn = null;

    #[ORM\Column(length: 200)]
    #[Assert\NotBlank]
    private ?string $institutionFr = null;

    #[ORM\Column(length: 200)]
    #[Assert\NotBlank]
    private ?string $degreeEn = null;

    #[ORM\Column(length: 200)]
    #[Assert\NotBlank]
    private ?string $degreeFr = null;

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

    public function getInstitutionEn(): ?string
    {
        return $this->institutionEn;
    }

    public function setInstitutionEn(string $institutionEn): self
    {
        $this->institutionEn = $institutionEn;

        return $this;
    }

    public function getInstitutionFr(): ?string
    {
        return $this->institutionFr;
    }

    public function setInstitutionFr(string $institutionFr): self
    {
        $this->institutionFr = $institutionFr;

        return $this;
    }

    public function getDegreeEn(): ?string
    {
        return $this->degreeEn;
    }

    public function setDegreeEn(string $degreeEn): self
    {
        $this->degreeEn = $degreeEn;

        return $this;
    }

    public function getDegreeFr(): ?string
    {
        return $this->degreeFr;
    }

    public function setDegreeFr(string $degreeFr): self
    {
        $this->degreeFr = $degreeFr;

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
