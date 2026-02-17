<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

abstract class SortableCrudController extends AbstractCrudController
{
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setDefaultSort(['sortOrder' => 'ASC', 'id' => 'ASC'])
            ->setPaginatorPageSize(200);
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (is_object($entityInstance) && method_exists($entityInstance, 'getSortOrder') && method_exists($entityInstance, 'setSortOrder')) {
            $currentSortOrder = (int) $entityInstance->getSortOrder();
            if ($currentSortOrder <= 0) {
                $entityClass = $entityInstance::class;
                $maxSortOrder = (int) $entityManager
                    ->createQuery(sprintf('SELECT COALESCE(MAX(e.sortOrder), 0) FROM %s e', $entityClass))
                    ->getSingleScalarResult();

                $entityInstance->setSortOrder($maxSortOrder + 1);
            }
        }

        parent::persistEntity($entityManager, $entityInstance);
    }
}

