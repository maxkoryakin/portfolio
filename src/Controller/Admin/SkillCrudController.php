<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Skill;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class SkillCrudController extends SortableCrudController
{
    public static function getEntityFqcn(): string
    {
        return Skill::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('nameEn'),
            TextField::new('nameFr'),
            TextField::new('categoryEn')->setRequired(false),
            TextField::new('categoryFr')->setRequired(false),
            IntegerField::new('sortOrder')->onlyOnIndex(),
            DateTimeField::new('createdAt')->onlyOnIndex(),
            DateTimeField::new('updatedAt')->onlyOnIndex(),
        ];
    }
}
