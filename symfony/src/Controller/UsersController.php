<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\AssignedItemsRepository;
use App\Repository\UserRepository;
use App\Service\PaginationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UsersController extends AbstractController
{
     #[Route('/users', name: 'app_users')]
    public function index(
        UserRepository $userRepository,
        PaginationService $paginationService,
        Request $request
    ): Response
    {

        $pagination = $paginationService->paginate(
            $userRepository->findAllOrdered(),
            $request->query->getInt('page', 1),
            10
        );
  
        return $this->render('users/index.html.twig', [
            'controller_name' => 'UsersController',
            'users' => $pagination['items'],
            'pagination' => $pagination
        ]);
    }

   #[Route('/users/new', name: 'app_users_new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $user = new User();

        $form = $this->createFormBuilder($user)
            ->add('name', TextType::class)
            ->add('surname', TextType::class)
            ->add('email', EmailType::class)
            ->add('department', TextType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'User created successfully!');

            return $this->redirectToRoute('app_users');
        }

        return $this->render('users/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route('/users/{id}/assigned-items', name: 'app_users_assigned_items')]
    public function assignedItems(
        int $id,
        AssignedItemsRepository $assignedItemsRepo,
        UserRepository $userRepo,
        PaginationService $paginationService,
        Request $request
    ): Response {

        $user = $userRepo->find($id);

        $pagination = $paginationService->paginate(
            $assignedItemsRepo->findByUserOrderedQB($id, 'createdAt', 'DESC'),
            $request->query->getInt('page', 1),
            10
        );
        
        return $this->render('users/assigned.html.twig', [
            'controller_name' => 'UsersController',
            'assignedItems' => $pagination['items'],
            'user' => $user,
            'pagination' => $pagination,
        ]);
    }
}
