<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Traits\TimestampableTrait;
use App\Repository\ProjectRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Project
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 200)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 200)]
    private ?string $titleEn = null;

    #[ORM\Column(length: 200)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 200)]
    private ?string $titleFr = null;

    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank]
    private ?string $shortDescriptionEn = null;

    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank]
    private ?string $shortDescriptionFr = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imagePath = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Url]
    private ?string $projectUrl = null;

    #[ORM\Column(options: ['default' => 0])]
    private int $sortOrder = 0;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitleEn(): ?string
    {
        return $this->titleEn;
    }

    public function setTitleEn(string $titleEn): self
    {
        $this->titleEn = $titleEn;

        return $this;
    }

    public function getTitleFr(): ?string
    {
        return $this->titleFr;
    }

    public function setTitleFr(string $titleFr): self
    {
        $this->titleFr = $titleFr;

        return $this;
    }

    public function getShortDescriptionEn(): ?string
    {
        return $this->shortDescriptionEn;
    }

    public function setShortDescriptionEn(string $shortDescriptionEn): self
    {
        $this->shortDescriptionEn = $shortDescriptionEn;

        return $this;
    }

    public function getShortDescriptionFr(): ?string
    {
        return $this->shortDescriptionFr;
    }

    public function setShortDescriptionFr(string $shortDescriptionFr): self
    {
        $this->shortDescriptionFr = $shortDescriptionFr;

        return $this;
    }

    public function getImagePath(): ?string
    {
        return $this->imagePath;
    }

    public function setImagePath(?string $imagePath): self
    {
        $this->imagePath = $imagePath;

        return $this;
    }

    public function getProjectUrl(): ?string
    {
        return $this->projectUrl;
    }

    public function setProjectUrl(?string $projectUrl): self
    {
        $this->projectUrl = $projectUrl;

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
