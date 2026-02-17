<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Traits\TimestampableTrait;
use App\Repository\SkillRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SkillRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Skill
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 150)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 150)]
    private ?string $nameEn = null;

    #[ORM\Column(length: 150)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 150)]
    private ?string $nameFr = null;

    #[ORM\Column(length: 150, nullable: true)]
    private ?string $categoryEn = null;

    #[ORM\Column(length: 150, nullable: true)]
    private ?string $categoryFr = null;

    #[ORM\Column(options: ['default' => 0])]
    private int $sortOrder = 0;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNameEn(): ?string
    {
        return $this->nameEn;
    }

    public function setNameEn(string $nameEn): self
    {
        $this->nameEn = $nameEn;

        return $this;
    }

    public function getNameFr(): ?string
    {
        return $this->nameFr;
    }

    public function setNameFr(string $nameFr): self
    {
        $this->nameFr = $nameFr;

        return $this;
    }

    public function getCategoryEn(): ?string
    {
        return $this->categoryEn;
    }

    public function setCategoryEn(?string $categoryEn): self
    {
        $this->categoryEn = $categoryEn;

        return $this;
    }

    public function getCategoryFr(): ?string
    {
        return $this->categoryFr;
    }

    public function setCategoryFr(?string $categoryFr): self
    {
        $this->categoryFr = $categoryFr;

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
