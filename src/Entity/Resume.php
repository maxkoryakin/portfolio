<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Traits\TimestampableTrait;
use App\Repository\ResumeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ResumeRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Resume
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 200, nullable: true)]
    #[Assert\Length(max: 200)]
    private ?string $titleEn = null;

    #[ORM\Column(length: 200, nullable: true)]
    #[Assert\Length(max: 200)]
    private ?string $titleFr = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $filePathEn = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $filePathFr = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitleEn(): ?string
    {
        return $this->titleEn;
    }

    public function setTitleEn(?string $titleEn): self
    {
        $this->titleEn = $titleEn;

        return $this;
    }

    public function getTitleFr(): ?string
    {
        return $this->titleFr;
    }

    public function setTitleFr(?string $titleFr): self
    {
        $this->titleFr = $titleFr;

        return $this;
    }

    public function getFilePathEn(): ?string
    {
        return $this->filePathEn;
    }

    public function setFilePathEn(?string $filePathEn): self
    {
        $this->filePathEn = $filePathEn;

        return $this;
    }

    public function getFilePathFr(): ?string
    {
        return $this->filePathFr;
    }

    public function setFilePathFr(?string $filePathFr): self
    {
        $this->filePathFr = $filePathFr;

        return $this;
    }
}
