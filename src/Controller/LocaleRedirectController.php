<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LocaleRedirectController extends AbstractController
{
    #[Route('/', name: 'root_redirect')]
    public function index(Request $request): Response
    {
        $preferred = $request->getPreferredLanguage(['en', 'fr']) ?? 'en';

        return $this->redirectToRoute('home', ['_locale' => $preferred]);
    }
}
