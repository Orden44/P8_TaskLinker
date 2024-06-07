<?php
// src/Controller/EmployeeController.php
namespace App\Controller;

use App\Form\EmployeeType;
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
    )
    {
    }

    #[Route('/employees', name: 'app_employees')]
    public function employees(): Response
    {
        $employees = $this->employeeRepository->findAll();

        return $this->render('/employees.html.twig', [
            'employees' => $employees,
        ]);
    }

    #[Route('/employee/{id}', name: 'app_employee')]
    public function employee(Request $request, EntityManagerInterface $entityManager, int $id): Response
    {
        $employee = $this->employeeRepository->find($id);

        if(!$employee) {
            return $this->redirectToRoute('app_error');
        }

        $form = $this->createform(EmployeeType::class, $employee);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $employee = $form->getData();

            $entityManager->persist($employee);
            $entityManager->flush();

            return $this->redirectToRoute('app_employees');
        }

        return $this->render('/employee.html.twig', [
            'form' => $form->createView(),
            'employee' => $employee,
        ]);
    }

    #[Route('/employee/{id}/delete', name: 'app_employee_delete')]
    public function deleteEmployee(EntityManagerInterface $entityManager,  int $id): Response
    {
        $employee = $this->employeeRepository->find($id);

        if(!$employee) {
            return $this->redirectToRoute('app_error');
        }

        $entityManager->remove($employee);
        $entityManager->flush();

        return $this->redirectToRoute('app_employees');
    }
}
