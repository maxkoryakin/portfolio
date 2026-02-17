<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\ContactInfo;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;

class ContactInfoCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ContactInfo::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('displayName'),
            ImageField::new('photoPath')
                ->setBasePath('/uploads/profile')
                ->setUploadDir('public/uploads/profile')
                ->setUploadedFileNamePattern('[randomhash].[extension]')
                ->setRequired(false),
            TextField::new('headlineEn'),
            TextField::new('headlineFr'),
            TextareaField::new('summaryEn'),
            TextareaField::new('summaryFr'),
            TextField::new('email'),
            TextField::new('phone')->setRequired(false),
            TextField::new('locationEn')->setRequired(false),
            TextField::new('locationFr')->setRequired(false),
            TextField::new('linkedinUrl')->setRequired(false),
            TextField::new('githubUrl')->setRequired(false),
            TextField::new('websiteUrl')->setRequired(false),
            DateTimeField::new('createdAt')->onlyOnIndex(),
            DateTimeField::new('updatedAt')->onlyOnIndex(),
        ];
    }
}
