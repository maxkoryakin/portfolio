<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Traits\TimestampableTrait;
use App\Repository\TestimonialRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TestimonialRepository::class)]
#[Assert\Expression(
    'this.getContentEn() or this.getContentFr()',
    message: 'At least one testimonial content translation is required.'
)]
#[ORM\HasLifecycleCallbacks]
class Testimonial
{
    use TimestampableTrait;

    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 150)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 150)]
    private ?string $authorName = null;

    #[ORM\Column(length: 150, nullable: true)]
    private ?string $authorRoleEn = null;

    #[ORM\Column(length: 150, nullable: true)]
    private ?string $authorRoleFr = null;

    #[ORM\Column(length: 150, nullable: true)]
    private ?string $companyEn = null;

    #[ORM\Column(length: 150, nullable: true)]
    private ?string $companyFr = null;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Assert\Length(min: 10)]
    private ?string $contentEn = null;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Assert\Length(min: 10)]
    private ?string $contentFr = null;

    #[ORM\Column(length: 20, options: ['default' => self::STATUS_PENDING])]
    #[Assert\Choice(choices: [self::STATUS_PENDING, self::STATUS_APPROVED, self::STATUS_REJECTED])]
    private string $status = self::STATUS_PENDING;

    #[ORM\Column(length: 2, options: ['default' => 'en'])]
    private string $submittedLocale = 'en';

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuthorName(): ?string
    {
        return $this->authorName;
    }

    public function setAuthorName(string $authorName): self
    {
        $this->authorName = $authorName;

        return $this;
    }

    public function getAuthorRoleEn(): ?string
    {
        return $this->authorRoleEn;
    }

    public function setAuthorRoleEn(?string $authorRoleEn): self
    {
        $this->authorRoleEn = $authorRoleEn;

        return $this;
    }

    public function getAuthorRoleFr(): ?string
    {
        return $this->authorRoleFr;
    }

    public function setAuthorRoleFr(?string $authorRoleFr): self
    {
        $this->authorRoleFr = $authorRoleFr;

        return $this;
    }

    public function getCompanyEn(): ?string
    {
        return $this->companyEn;
    }

    public function setCompanyEn(?string $companyEn): self
    {
        $this->companyEn = $companyEn;

        return $this;
    }

    public function getCompanyFr(): ?string
    {
        return $this->companyFr;
    }

    public function setCompanyFr(?string $companyFr): self
    {
        $this->companyFr = $companyFr;

        return $this;
    }

    public function getContentEn(): ?string
    {
        return $this->contentEn;
    }

    public function setContentEn(?string $contentEn): self
    {
        $this->contentEn = $contentEn;

        return $this;
    }

    public function getContentFr(): ?string
    {
        return $this->contentFr;
    }

    public function setContentFr(?string $contentFr): self
    {
        $this->contentFr = $contentFr;

        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getSubmittedLocale(): string
    {
        return $this->submittedLocale;
    }

    public function setSubmittedLocale(string $submittedLocale): self
    {
        $this->submittedLocale = $submittedLocale;

        return $this;
    }
}
