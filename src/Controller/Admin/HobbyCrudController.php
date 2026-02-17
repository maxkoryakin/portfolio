<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Hobby;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class HobbyCrudController extends SortableCrudController
{
    public static function getEntityFqcn(): string
    {
        return Hobby::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('nameEn'),
            TextField::new('nameFr'),
            TextareaField::new('descriptionEn')->setRequired(false),
            TextareaField::new('descriptionFr')->setRequired(false),
            TextField::new('icon')
                ->setRequired(false)
                ->setHelp('Use one emoji or short symbol (for example: guitar emoji, camera emoji).'),
            IntegerField::new('sortOrder')->onlyOnIndex(),
            DateTimeField::new('createdAt')->onlyOnIndex(),
            DateTimeField::new('updatedAt')->onlyOnIndex(),
        ];
    }
}
