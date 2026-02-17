<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Testimonial;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\RedirectResponse;

class TestimonialCrudController extends AbstractCrudController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private AdminUrlGenerator $adminUrlGenerator
    )
    {
    }

    public static function getEntityFqcn(): string
    {
        return Testimonial::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('authorName'),
            TextField::new('authorRoleEn')->setRequired(false),
            TextField::new('authorRoleFr')->setRequired(false),
            TextField::new('companyEn', 'Company / Institution (EN)')->setRequired(false),
            TextField::new('companyFr', 'Company / Institution (FR)')->setRequired(false),
            TextareaField::new('contentEn')->setRequired(false),
            TextareaField::new('contentFr')->setRequired(false),
            ChoiceField::new('status')
                ->setChoices([
                    'Pending' => Testimonial::STATUS_PENDING,
                    'Approved' => Testimonial::STATUS_APPROVED,
                    'Rejected' => Testimonial::STATUS_REJECTED,
                ])
                ->renderAsBadges([
                    Testimonial::STATUS_PENDING => 'warning',
                    Testimonial::STATUS_APPROVED => 'success',
                    Testimonial::STATUS_REJECTED => 'danger',
                ]),
            TextField::new('submittedLocale')->onlyOnIndex(),
            DateTimeField::new('createdAt')->onlyOnIndex(),
            DateTimeField::new('updatedAt')->onlyOnIndex(),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        $approve = Action::new('approve', 'Approve', 'fa fa-check')
            ->linkToCrudAction('approve')
            ->displayIf(fn (Testimonial $testimonial) => $testimonial->getStatus() !== Testimonial::STATUS_APPROVED);

        $reject = Action::new('reject', 'Reject', 'fa fa-times')
            ->linkToCrudAction('reject')
            ->displayIf(fn (Testimonial $testimonial) => $testimonial->getStatus() !== Testimonial::STATUS_REJECTED);

        return $actions
            ->disable(Action::NEW, Action::EDIT)
            ->add(Crud::PAGE_INDEX, $approve)
            ->add(Crud::PAGE_INDEX, $reject);
    }

    public function approve(AdminContext $context): RedirectResponse
    {
        $testimonial = $context->getEntity()->getInstance();
        if ($testimonial instanceof Testimonial) {
            $testimonial->setStatus(Testimonial::STATUS_APPROVED);
            $this->entityManager->flush();
        }

        return $this->redirect($this->resolveRedirectUrl($context));
    }

    public function reject(AdminContext $context): RedirectResponse
    {
        $testimonial = $context->getEntity()->getInstance();
        if ($testimonial instanceof Testimonial) {
            $testimonial->setStatus(Testimonial::STATUS_REJECTED);
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
