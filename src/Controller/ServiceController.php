<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ServiceController extends AbstractController
{
    #[Route('/service', name: 'app_service')]
    public function index(): Response
    {
        return $this->render('service/index.html.twig', [
            'controller_name' => 'ServiceController',
        ]);
    }
    #[Route('/service/{name}', name: 'app_name')]
    public function showService($name): Response
    {
        return $this->render('service/showService.html.twig', [
            'name' => $name
        ]);
    }
    #[Route('/service/redirect', name: 'app_goindex')]
    public function goToIndex(): Response
    {
        return $this->redirectToRoute('app_service');
    }
}
