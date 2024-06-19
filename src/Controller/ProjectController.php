<?php
// src/Controller/ProjectController.php
namespace App\Controller;

use App\Entity\Project;
use App\Form\ProjectType;
use App\Form\EditProjectType;
use App\Repository\ProjectRepository;
use App\Repository\EmployeeRepository;
use App\Repository\StatusRepository;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProjectController extends AbstractController
{
    public function __construct(
        private ProjectRepository $projectRepository,
        private TaskRepository $taskRepository,
        private EmployeeRepository $employeeRepository,
        private StatusRepository $statusRepository,
        private EntityManagerInterface $entityManager,
    )
    {
    }

    #[Route('/project/add', name: 'app_project_add')]
    public function createProject(Request $request, EntityManagerInterface $entityManager): Response
    {
        $project = new Project();

        $form = $this->createform(ProjectType::class, $project);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $project = $form->getData();

            $entityManager->persist($project);
            $entityManager->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('/new-project.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/project/edit/{id}', name: 'app_project_edit')]
    public function editProject(Request $request, EntityManagerInterface $entityManager, int $id): Response
    {
        $project = $this->projectRepository->find($id);
        if(!$project) {
            return $this->redirectToRoute('app_error');
        }

        $form = $this->createform(EditProjectType::class, $project);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $project = $form->getData();

            $entityManager->persist($project);
            $entityManager->flush();

            return $this->redirectToRoute('app_project', ['id' => $project->getId()]);
        }

        return $this->render('/edit-project.html.twig', [
            'form' => $form->createView(),
            'project' => $project,
        ]);
    }

    #[Route('/project/{id}', name: 'app_project')]
    public function project(int $id): Response
    {
        $project = $this->projectRepository->find($id);
        $tasks = $this->taskRepository->findByProjectId($id);
        $task = $this->taskRepository->find($id);
        $employees = $this->employeeRepository->findAll();
        $employees = $project->getEmployees();
        $statuses = $this->statusRepository->findAll();

        if(!$project) {
            return $this->redirectToRoute('app_error');
        }
        return $this->render('/project.html.twig', [
            'project' => $project,
            'tasks' => $tasks,
            'task' => $task,
            'employees' => $employees,
            'statuses' => $statuses,
        ]);
    }

    #[Route('/project/{id}/delete', name: 'app_project_delete')]
    public function deleteProject(ProjectRepository $projectRepository, EntityManagerInterface $entityManager,  int $id): Response
    {
        $project = $projectRepository->find($id);

        if(!$project) {
            return $this->redirectToRoute('app_error');
        }

        $entityManager->remove($project);
        $entityManager->flush();

        return $this->redirectToRoute('app_home');
    }
}
