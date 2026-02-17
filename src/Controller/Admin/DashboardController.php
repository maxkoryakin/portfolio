<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\ContactInfo;
use App\Entity\ContactMessage;
use App\Entity\Education;
use App\Entity\Hobby;
use App\Entity\Project;
use App\Entity\Resume;
use App\Entity\Skill;
use App\Entity\Testimonial;
use App\Entity\User;
use App\Entity\WorkExperience;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
class DashboardController extends AbstractDashboardController
{
    public function __construct(private AdminUrlGenerator $adminUrlGenerator)
    {
    }

    #[Route('/admin', name: 'admin_dashboard')]
    public function index(): Response
    {
        $url = $this->adminUrlGenerator
            ->setController(ProjectCrudController::class)
            ->generateUrl();

        return $this->redirect($url);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Portfolio Admin');
    }

    public function configureAssets(): Assets
    {
        return Assets::new()
            ->addJsFile('js/admin-sort-order.js');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToRoute('View Website', 'fa fa-globe', 'home', ['_locale' => 'en']);
        yield MenuItem::section('Content');
        yield MenuItem::linkToCrud('Projects', 'fa fa-folder-open', Project::class);
        yield MenuItem::linkToCrud('Skills', 'fa fa-star', Skill::class);
        yield MenuItem::linkToCrud('Work Experience', 'fa fa-briefcase', WorkExperience::class);
        yield MenuItem::linkToCrud('Education', 'fa fa-graduation-cap', Education::class);
        yield MenuItem::linkToCrud('Hobbies', 'fa fa-heart', Hobby::class);
        yield MenuItem::linkToCrud('Resume', 'fa fa-file', Resume::class);
        yield MenuItem::linkToCrud('Contact Info', 'fa fa-address-card', ContactInfo::class);
        yield MenuItem::section('Engagement');
        yield MenuItem::linkToCrud('Testimonials', 'fa fa-comments', Testimonial::class);
        yield MenuItem::linkToCrud('Contact Messages', 'fa fa-envelope', ContactMessage::class);
        yield MenuItem::section('Administration');
        yield MenuItem::linkToCrud('Admins', 'fa fa-user-shield', User::class);
    }

    #[Route('/admin/reorder-sort-order', name: 'admin_reorder_sort_order', methods: ['POST'])]
    public function reorderSortOrder(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $payload = json_decode($request->getContent(), true);
        if (!is_array($payload)) {
            return new JsonResponse(['ok' => false, 'message' => 'Invalid payload.'], Response::HTTP_BAD_REQUEST);
        }

        $crudController = $payload['crudController'] ?? null;
        $ids = $payload['ids'] ?? null;

        if (!is_string($crudController) || !is_array($ids)) {
            return new JsonResponse(['ok' => false, 'message' => 'Missing data.'], Response::HTTP_BAD_REQUEST);
        }

        $entityByCrudController = [
            ProjectCrudController::class => Project::class,
            SkillCrudController::class => Skill::class,
            WorkExperienceCrudController::class => WorkExperience::class,
            EducationCrudController::class => Education::class,
            HobbyCrudController::class => Hobby::class,
        ];

        $entityClass = $entityByCrudController[$crudController] ?? null;
        if ($entityClass === null) {
            return new JsonResponse(['ok' => false, 'message' => 'Unsupported controller.'], Response::HTTP_BAD_REQUEST);
        }

        $repository = $entityManager->getRepository($entityClass);
        $position = 1;

        foreach ($ids as $id) {
            if (!is_numeric($id)) {
                continue;
            }

            $entity = $repository->find((int) $id);
            if ($entity === null || !method_exists($entity, 'setSortOrder')) {
                continue;
            }

            $entity->setSortOrder($position);
            $position++;
        }

        $entityManager->flush();

        return new JsonResponse(['ok' => true]);
    }
}
