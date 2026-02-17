<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserCrudController extends AbstractCrudController
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {
    }

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $passwordField = TextField::new('plainPassword')
            ->setFormType(PasswordType::class)
            ->setRequired($pageName === Crud::PAGE_NEW)
            ->onlyOnForms();

        return [
            TextField::new('email'),
            $passwordField,
            ChoiceField::new('roles')
                ->allowMultipleChoices()
                ->setChoices([
                    'Admin' => 'ROLE_ADMIN',
                ]),
            DateTimeField::new('createdAt')->onlyOnIndex(),
            DateTimeField::new('updatedAt')->onlyOnIndex(),
        ];
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (!$entityInstance instanceof User) {
            return;
        }

        $this->hashPassword($entityInstance);
        $entityManager->persist($entityInstance);
        $entityManager->flush();
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (!$entityInstance instanceof User) {
            return;
        }

        $this->hashPassword($entityInstance);
        $entityManager->persist($entityInstance);
        $entityManager->flush();
    }

    private function hashPassword(User $user): void
    {
        $plainPassword = $user->getPlainPassword();
        if ($plainPassword) {
            $user->setPassword($this->passwordHasher->hashPassword($user, $plainPassword));
            $user->setPlainPassword(null);
        }
    }
}
