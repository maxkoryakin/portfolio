<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Project;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;

class ProjectCrudController extends SortableCrudController
{
    public static function getEntityFqcn(): string
    {
        return Project::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $imageField = ImageField::new('imagePath')
            ->setBasePath('/uploads/projects')
            ->setUploadDir('public/uploads/projects')
            ->setUploadedFileNamePattern('[randomhash].[extension]')
            ->setRequired(false);

        return [
            TextField::new('titleEn'),
            TextField::new('titleFr'),
            TextareaField::new('shortDescriptionEn'),
            TextareaField::new('shortDescriptionFr'),
            $imageField,
            UrlField::new('projectUrl')->setRequired(false),
            IntegerField::new('sortOrder')->onlyOnIndex(),
            DateTimeField::new('createdAt')->onlyOnIndex(),
            DateTimeField::new('updatedAt')->onlyOnIndex(),
        ];
    }
}
