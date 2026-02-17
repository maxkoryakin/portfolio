<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\ContactMessage;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ContactMessageCrudController extends AbstractCrudController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private AdminUrlGenerator $adminUrlGenerator
    ) {
    }

    public static function getEntityFqcn(): string
    {
        return ContactMessage::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        $markRead = Action::new('markRead', 'Mark read', 'fa fa-check')
            ->linkToCrudAction('markRead')
            ->displayIf(fn (ContactMessage $message) => !$message->isRead());

        $markUnread = Action::new('markUnread', 'Mark unread', 'fa fa-undo')
            ->linkToCrudAction('markUnread')
            ->displayIf(fn (ContactMessage $message) => $message->isRead());

        return $actions
            ->disable(Action::NEW, Action::EDIT, Action::DELETE)
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->add(Crud::PAGE_INDEX, $markRead)
            ->add(Crud::PAGE_INDEX, $markUnread);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'),
            TextField::new('email'),
            TextField::new('subject'),
            TextareaField::new('message')->onlyOnDetail(),
            TextField::new('locale')->onlyOnIndex(),
            BooleanField::new('isRead'),
            DateTimeField::new('createdAt')->onlyOnIndex(),
        ];
    }

    public function markRead(AdminContext $context): RedirectResponse
    {
        $message = $context->getEntity()->getInstance();
        if ($message instanceof ContactMessage) {
            $message->setIsRead(true);
            $this->entityManager->flush();
        }

        return $this->redirect($this->resolveRedirectUrl($context));
    }

    public function markUnread(AdminContext $context): RedirectResponse
    {
        $message = $context->getEntity()->getInstance();
        if ($message instanceof ContactMessage) {
            $message->setIsRead(false);
            $this->entityManager->flush();
        }

        return $this->redirect($this->resolveRedirectUrl($context));
    }

    private function resolveRedirectUrl(AdminContext $context): string
    {
        $referrer = $context->getReferrer();
        if (is_string($referrer) && $referrer !== '') {
            return $referrer;
        }

        return $this->adminUrlGenerator
            ->setController(self::class)
            ->setAction(Crud::PAGE_INDEX)
            ->generateUrl();
    }
}
