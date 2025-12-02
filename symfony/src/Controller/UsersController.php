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
            $assignedItemsRepo->findByUserOrderedQB($id, 'assignedAt', 'DESC'),
            $request->query->getInt('page', 1),
            10
        );

        // dd($pagination['items']);

        return $this->render('users/assigned.html.twig', [
            'controller_name' => 'UsersController',
            'assignedItems' => $pagination['items'],
            'user' => $user,
            'pagination' => $pagination,
        ]);
    }

    #[Route('/users/{id}/delete', name: 'app_user_delete', methods: ['POST'])]
    public function deleteUser(
        User $user,
        AssignedItemsRepository $assignedItemsRepo,
        EntityManagerInterface $em
    ): Response {

        if ($assignedItemsRepo->count(['userId' => $user->getId()]) > 0) {
            $this->addFlash('error', 'Cannot delete this user because they have assigned devices.');
            return $this->redirectToRoute('app_users'); 
        }
        
        $em->remove($user);
        $em->flush();

        $this->addFlash('success', 'User deleted successfully.');

        return $this->redirectToRoute('app_users');
    }

    #[Route('/users/{userId}/assigned-items/{assignedItemId}/unassign', name: 'app_user_unassign_item', methods: ['POST'])]
    public function unassignItem(
        int $userId,
        int $assignedItemId,
        AssignedItemsRepository $assignedItemsRepo,
        EntityManagerInterface $em
    ): Response {
        $assignedItem = $assignedItemsRepo->find($assignedItemId);

        if (!$assignedItem) {
            throw $this->createNotFoundException('Assigned item not found.');
        }

        $inventory = $assignedItem->getInventoryId();

        if ($inventory) {
            $inventory->setAvailable($inventory->getAvailable() + 1);
        }

        $em->remove($assignedItem);
        $em->flush();

        $this->addFlash('success', 'Item unassigned successfully.');

        return $this->redirectToRoute('app_users_assigned_items', ['id' => $userId]);
    }

}