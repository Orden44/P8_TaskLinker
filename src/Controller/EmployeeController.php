<?php
// src/Controller/EmployeeController.php
namespace App\Controller;

use App\Entity\Employee;
use App\Form\ProjectType;
use App\Repository\EmployeeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class EmployeeController extends AbstractController
{
    public function __construct(
        private EmployeeRepository $employeeRepository,
        private EntityManagerInterface $entityManager,
    )
    {
    }

    #[Route('/employees', name: 'app_employees')]
    public function employees(): Response
    {
        return $this->render('/index.html.twig', [
            'controller_name' => 'Controller',
        ]);
    }
}
