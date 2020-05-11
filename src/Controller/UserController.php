<?php

namespace App\Controller;

use App\Entity\User;
use App\Factory\UserFactory;
use App\Form\UserType;
use App\Repository\UserRepository;
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
    public function list(UserRepository $repository)
    {
        return $this->render('user/list.html.twig', ['users' => $repository->findAllExceptAnonymous()]);
    }

    /**
     * @Route("/users/create", name="user_create")
     */
    public function create(Request $request, UserService $service, UserFactory $factory)
    {
        $user = $factory->create($this->getUser());
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $service->manager($user, $request->request->get('user'));
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
    public function edit(User $user, Request $request, UserService $service)
    {
        $form = $this->createForm(UserType::class, $user, ['required'=>false]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $service->manager($user, $request->request->get('user'));
            $service->save($user);

            $this->addFlash('success', "L'utilisateur a bien été modifié");

            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/edit.html.twig', ['form' => $form->createView(), 'user' => $user]);
    }
}
