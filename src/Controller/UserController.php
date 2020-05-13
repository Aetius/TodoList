<?php

namespace App\Controller;

use App\Entity\User;
use App\Factory\UserFactory;
use App\Form\UserType;
use App\Service\FormService;
use App\Service\UserService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/users", name="user_list")
     *
     * @IsGranted("admin_access")
     */
    public function list(UserService $service)
    {
        $users = $service->getUsers();
        return $this->render('user/list.html.twig', ['users' => $users]);
    }

    /**
     * @Route("/users/create", name="user_create")
     */
    public function create(Request $request, UserService $service, UserFactory $factory, FormService $formService)
    {
        $roleChoice = $formService->defineRole();
        $user = $factory->create($this->getUser());
        $form = $this->createForm(UserType::class, $user, ['required'=>true, 'with_role_choice'=>$roleChoice]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $service->manager($user);
            $service->save($user);

            $this->addFlash('success', "L'utilisateur a bien été ajouté.");

            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/users/{id}/edit", name="user_edit")
     *
     * @IsGranted("edit_user", subject="user")
     */
    public function edit(User $user, Request $request, UserService $service, FormService $formService)
    {
        $roleChoice = $formService->defineRole();
        $form = $this->createForm(UserType::class, $user, ['required'=>false, 'with_role_choice'=>$roleChoice]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $service->manager($user);
            $service->save($user);

            $this->addFlash('success', "L'utilisateur a bien été modifié");

            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/edit.html.twig', ['form' => $form->createView(), 'user' => $user]);
    }
}
