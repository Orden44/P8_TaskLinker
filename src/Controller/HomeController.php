<?php
// src/Controller/Home.php
namespace App\Controller;

use App\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    public function __construct(
        private ProjectRepository $projectRepository,
    )
    {
    }

    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        $projects = $this->projectRepository->findAll();

        return $this->render('/index.html.twig', [
            'projects' => $projects,
        ]);
    }

    /**
     * Page d'erreur
     */
    #[Route('/errorPage', name: 'app_error')]
    public function error(): Response
    {
        return $this->render('error.html.twig', ['error' => 'Cette référence est inexixtante']); 
    }
}
