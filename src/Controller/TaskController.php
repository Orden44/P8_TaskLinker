<?php
// src/Controller/TaskController.php
namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use App\Repository\EmployeeRepository;
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
        private EntityManagerInterface $entityManager,
    )
    {
    }

    #[Route('/task/add', name: 'app_task_add')]
    public function editTask(Request $request, EntityManagerInterface $entityManager): Response
    {
        $task = new Task();

        $form = $this->createform(TaskType::class, $task);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();

            $entityManager->persist($task);
            $entityManager->flush();

            return $this->redirectToRoute('app_task', ['id' => $task->getId()]);
        }

        return $this->render('/new-task.html.twig', [
            'form' => $form->createView(),
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
            'form' => $form->createView(),
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
