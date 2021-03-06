<?php

namespace App\Controller;

use App\Entity\Task;
use App\Factory\TaskFactory;
use App\Form\TaskType;
use App\Service\TaskService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{
    /**
     * @Route("/tasks", name="task_list", methods={"GET"})
     *
     * @IsGranted("task_show")
     */
    public function list(TaskService $service)
    {
        $tasks = $service->show($this->getUser());

        return $this->render('task/list.html.twig', ['tasks' => $tasks]);
    }

    /**
     * @Route("/tasks/create", name="task_create", methods={"GET", "POST"})
     *
     * @IsGranted("task_create")
     */
    public function create(Request $request, TaskService $service, TaskFactory $factory)
    {
        $task = $factory->create($this->getUser());
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $service->save($task);

            $this->addFlash('success', 'La tâche a été bien été ajoutée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/tasks/{id}/edit", name="task_edit", methods={"GET", "POST"})
     *
     * @IsGranted("task_edit", subject="task")
     */
    public function edit(Task $task, Request $request, TaskService $service)
    {
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $service->save($task);
            $this->addFlash('success', 'La tâche a bien été modifiée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/edit.html.twig', [
            'form' => $form->createView(),
            'task' => $task,
        ]);
    }

    /**
     * @Route("/tasks/{id}/toggle", name="task_toggle", methods={"POST"})
     *
     * @IsGranted("task_edit", subject="task")
     */
    public function toggleTask(Task $task, TaskService $service, Request $request)
    {
        if ($this->isCsrfTokenValid('toggle-'.$task->getId(), $request->get('token'))) {
            $task = $service->updateToggle($task);
            $service->save($task);
            $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme faite.', $task->getTitle()));
        }

        return $this->redirectToRoute('task_list');
    }

    /**
     * @Route("/tasks/{id}/delete", name="task_delete", methods={"POST"})
     *
     * @IsGranted("task_delete", subject="task")
     */
    public function deleteTask(Task $task, TaskService $service, Request $request)
    {
        if ($this->isCsrfTokenValid('delete-'.$task->getId(), $request->get('token'))) {
            $service->delete($task);
            $this->addFlash('success', 'La tâche a bien été supprimée.');
        }

        return $this->redirectToRoute('task_list');
    }
}
