<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AboutController extends AbstractController
{
    #[Route('/{_locale}/about', name: 'app_about', requirements: ['_locale' => 'en|fr'], defaults: ['_locale' => 'en'])]
    public function index(): Response
    {
        return $this->render('about/index.html.twig', [
            'current_menu' => 'About',
        ]);
    }
}

