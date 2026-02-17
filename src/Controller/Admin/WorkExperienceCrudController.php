<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\WorkExperience;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class WorkExperienceCrudController extends SortableCrudController
{
    public static function getEntityFqcn(): string
    {
        return WorkExperience::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('companyEn'),
            TextField::new('companyFr'),
            TextField::new('roleEn'),
            TextField::new('roleFr'),
            TextField::new('locationEn')->setRequired(false),
            TextField::new('locationFr')->setRequired(false),
            DateField::new('startDate'),
            DateField::new('endDate')->setRequired(false),
            BooleanField::new('isCurrent'),
            TextareaField::new('descriptionEn'),
            TextareaField::new('descriptionFr'),
            IntegerField::new('sortOrder')->onlyOnIndex(),
            DateTimeField::new('createdAt')->onlyOnIndex(),
            DateTimeField::new('updatedAt')->onlyOnIndex(),
        ];
    }
}
