<?php
// src/Controller/TaskController.php
namespace App\Controller;

use App\Entity\Status;
use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use App\Repository\EmployeeRepository;
use App\Repository\ProjectRepository;
use App\Repository\StatusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TaskController extends AbstractController
{
    public function __construct(
        private TaskRepository $taskRepository,
        private EmployeeRepository $employeeRepository,
        private ProjectRepository $projectRepository,
        private EntityManagerInterface $entityManager,
        private StatusRepository $statusRepository,
    )
    {
    }
    
    #[Route('/task{id}/add/{status}', name: 'app_task_add')]
    public function createTask(Request $request, EntityManagerInterface $entityManager, int $id, int $status): Response
    {
        $project = $this->projectRepository->find($id);
        $current_status = $this->statusRepository->find($status);
        $employees = $this->employeeRepository->findAll();
        $employees = $project->getEmployees();
        $task = new Task();
        $task->setProject($project);
        $task->setStatus($current_status);

        $form = $this->createform(TaskType::class, $task);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();

            $entityManager->persist($task);
            $entityManager->flush();

            return $this->redirectToRoute('app_task', ['id' => $task->getId()]);
        }

        return $this->render('/new-task.html.twig', [
            'form' => $form,
            'employees' => $employees,
            'task' => $task,
        ]);
    }

    #[Route('/task/{id}', name: 'app_task')]
    public function task(Request $request, EntityManagerInterface $entityManager, int $id): Response
    {
        $task = $this->taskRepository->find($id);

        if(!$task) {
            return $this->redirectToRoute('app_error');
        }

        $form = $this->createform(TaskType::class, $task);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();

            $entityManager->persist($task);
            $entityManager->flush();

            return $this->redirectToRoute('app_task', ['id' => $task->getId()]);
        }

        return $this->render('/task.html.twig', [
            'form' => $form,
            'task' => $task,
        ]);
    }

    #[Route('/task/{id}/delete', name: 'app_task_delete')]
    public function deleteTask(TaskRepository $taskRepository, EntityManagerInterface $entityManager,  int $id): Response
    {
        $task = $taskRepository->find($id);

        if(!$task) {
            return $this->redirectToRoute('app_error');
        }

        $entityManager->remove($task);
        $entityManager->flush();

        return $this->redirectToRoute('app_home');
    }
}
