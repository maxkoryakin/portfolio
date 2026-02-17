<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\ContactMessage;
use App\Entity\Testimonial;
use App\Form\ContactMessageType;
use App\Form\TestimonialSubmissionType;
use App\Repository\ContactInfoRepository;
use App\Repository\EducationRepository;
use App\Repository\HobbyRepository;
use App\Repository\ProjectRepository;
use App\Repository\ResumeRepository;
use App\Repository\SkillRepository;
use App\Repository\TestimonialRepository;
use App\Repository\WorkExperienceRepository;
use App\Service\RecaptchaVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/{_locale}', requirements: ['_locale' => 'en|fr'], defaults: ['_locale' => 'en'])]
class PortfolioController extends AbstractController
{
    public function __construct(
        private ContactInfoRepository $contactInfoRepository,
        private ResumeRepository $resumeRepository,
        private RecaptchaVerifier $recaptchaVerifier,
        private TranslatorInterface $translator
    ) {
    }

    #[Route('/', name: 'home', methods: ['GET'])]
    public function home(
        ProjectRepository $projectRepository,
        SkillRepository $skillRepository,
        WorkExperienceRepository $workExperienceRepository,
        EducationRepository $educationRepository,
        HobbyRepository $hobbyRepository,
        TestimonialRepository $testimonialRepository
    ): Response {
        $projects = $projectRepository->findBy([], ['sortOrder' => 'ASC', 'createdAt' => 'DESC'], 3);
        $testimonials = $testimonialRepository->findApproved();

        $stats = [
            'projects' => $projectRepository->count([]),
            'skills' => $skillRepository->count([]),
            'experience' => $workExperienceRepository->count([]),
            'education' => $educationRepository->count([]),
            'hobbies' => $hobbyRepository->count([]),
            'testimonials' => $testimonialRepository->count(['status' => Testimonial::STATUS_APPROVED]),
        ];

        return $this->render('portfolio/home.html.twig', array_merge($this->baseContext(), [
            'projects' => $projects,
            'testimonials' => array_slice($testimonials, 0, 3),
            'stats' => $stats,
        ]));
    }

    #[Route('/projects', name: 'projects', methods: ['GET'])]
    public function projects(ProjectRepository $projectRepository): Response
    {
        $projects = $projectRepository->findBy([], ['sortOrder' => 'ASC', 'createdAt' => 'DESC']);

        return $this->render('portfolio/projects.html.twig', array_merge($this->baseContext(), [
            'projects' => $projects,
        ]));
    }

    #[Route('/skills', name: 'skills', methods: ['GET'])]
    public function skills(SkillRepository $skillRepository): Response
    {
        $skills = $skillRepository->findBy([], ['categoryEn' => 'ASC', 'sortOrder' => 'ASC', 'nameEn' => 'ASC']);

        return $this->render('portfolio/skills.html.twig', array_merge($this->baseContext(), [
            'skills' => $skills,
        ]));
    }

    #[Route('/experience', name: 'experience', methods: ['GET'])]
    public function experience(WorkExperienceRepository $workExperienceRepository): Response
    {
        $experiences = $workExperienceRepository->findBy([], ['sortOrder' => 'ASC', 'startDate' => 'DESC']);

        return $this->render('portfolio/experience.html.twig', array_merge($this->baseContext(), [
            'experiences' => $experiences,
        ]));
    }

    #[Route('/education', name: 'education', methods: ['GET'])]
    public function education(EducationRepository $educationRepository): Response
    {
        $educations = $educationRepository->findBy([], ['sortOrder' => 'ASC', 'startDate' => 'DESC']);

        return $this->render('portfolio/education.html.twig', array_merge($this->baseContext(), [
            'educations' => $educations,
        ]));
    }

    #[Route('/resume', name: 'resume', methods: ['GET'])]
    public function resume(): Response
    {
        $resume = $this->resumeRepository->findOneBy([], ['id' => 'ASC']);

        return $this->render('portfolio/resume.html.twig', array_merge($this->baseContext(), [
            'resume' => $resume,
        ]));
    }

    #[Route('/hobbies', name: 'hobbies', methods: ['GET'])]
    public function hobbies(HobbyRepository $hobbyRepository): Response
    {
        $hobbies = $hobbyRepository->findBy([], ['sortOrder' => 'ASC', 'createdAt' => 'DESC']);

        return $this->render('portfolio/hobbies.html.twig', array_merge($this->baseContext(), [
            'hobbies' => $hobbies,
        ]));
    }

    #[Route('/testimonials', name: 'testimonials', methods: ['GET', 'POST'])]
    public function testimonials(
        Request $request,
        TestimonialRepository $testimonialRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $testimonial = new Testimonial();
        $form = $this->createForm(TestimonialSubmissionType::class, $testimonial, [
            'locale' => $request->getLocale(),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $captchaToken = $request->request->get('g-recaptcha-response');
            if (!$this->recaptchaVerifier->verify(is_string($captchaToken) ? $captchaToken : null, $request->getClientIp())) {
                $form->addError(new FormError($this->translator->trans('flash.testimonial_captcha_failed')));
            }
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $testimonial->setStatus(Testimonial::STATUS_PENDING);
            $testimonial->setSubmittedLocale($request->getLocale());

            $entityManager->persist($testimonial);
            $entityManager->flush();

            $this->addFlash('success', $this->translator->trans('flash.testimonial_submitted'));

            return $this->redirectToRoute('testimonials', ['_locale' => $request->getLocale()]);
        }

        $approvedTestimonials = $testimonialRepository->findApproved();

        return $this->render('portfolio/testimonials.html.twig', array_merge($this->baseContext(), [
            'testimonials' => $approvedTestimonials,
            'form' => $form->createView(),
            'recaptcha_enabled' => $this->recaptchaVerifier->isConfigured(),
            'recaptcha_site_key' => $this->recaptchaVerifier->getSiteKey(),
        ]));
    }

    #[Route('/contact', name: 'contact', methods: ['GET', 'POST'])]
    public function contact(Request $request, EntityManagerInterface $entityManager): Response
    {
        $message = new ContactMessage();
        $form = $this->createForm(ContactMessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $captchaToken = $request->request->get('g-recaptcha-response');
            if (!$this->recaptchaVerifier->verify(is_string($captchaToken) ? $captchaToken : null, $request->getClientIp())) {
                $form->addError(new FormError($this->translator->trans('flash.contact_captcha_failed')));
            }
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $message->setLocale($request->getLocale());

            $entityManager->persist($message);
            $entityManager->flush();

            $this->addFlash('success', $this->translator->trans('flash.contact_sent'));

            return $this->redirectToRoute('contact', ['_locale' => $request->getLocale()]);
        }

        return $this->render('portfolio/contact.html.twig', array_merge($this->baseContext(), [
            'form' => $form->createView(),
            'recaptcha_enabled' => $this->recaptchaVerifier->isConfigured(),
            'recaptcha_site_key' => $this->recaptchaVerifier->getSiteKey(),
        ]));
    }

    private function baseContext(): array
    {
        return [
            'contactInfo' => $this->contactInfoRepository->findPrimary(),
        ];
    }
}
