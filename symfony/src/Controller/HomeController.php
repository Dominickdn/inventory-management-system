<?php

namespace App\Controller;

use App\Entity\AssignedItems;
use App\Entity\Inventory;
use App\Form\AssignUserType;
use App\Repository\AssignedItemsRepository;
use App\Repository\InventoryRepository;
use App\Service\PaginationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{

        #[Route('/', name: 'app_home')]
        public function index(
            InventoryRepository $inventoryRepo,
            PaginationService $paginationService,
            Request $request
        ): Response
        {
            $pagination = $paginationService->paginate(
                $inventoryRepo->findAllOrdered(),
                $request->query->getInt('page', 1),
                10
            );
    
            return $this->render('home/index.html.twig', [
                'controller_name' => 'UsersController',
                'inventory' => $pagination['items'],
                'pagination' => $pagination
            ]);
        }
        
        #[Route('/new', name: 'app_home_new')]
        public function new(Request $request, EntityManagerInterface $em): Response
        {
            $inventory = new Inventory();

            $form = $this->createFormBuilder($inventory)
                ->add('name', TextType::class)
                ->add('type', TextType::class)
                ->add('description', TextType::class)
                ->getForm();

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $em->persist($inventory);
                $em->flush();

                $this->addFlash('success', 'User created successfully!');

                return $this->redirectToRoute('app_home');
            }

            return $this->render('/home/new.html.twig', [
                'form' => $form->createView(),
            ]);
        }

        #[Route('/{id}/delete', name: 'app_home_delete', methods: ['POST'])]
        public function deleteInventory(
            Inventory $inventory,
            AssignedItemsRepository $assignedRepo,
            EntityManagerInterface $em
        ): Response {

            // Prevent deletion if inventory still has assigned items
            $assignedCount = $assignedRepo->count(['inventoryId' => $inventory]);

            if ($assignedCount > 0) {
                $this->addFlash('error', 'Cannot delete this item because it has assigned devices.');
                return $this->redirectToRoute('app_home'); 
            }

            $em->remove($inventory);
            $em->flush();

            $this->addFlash('success', 'Inventory item deleted successfully.');

            return $this->redirectToRoute('app_home');
        }

        #[Route('/{id}/adjust/{field}/{action}', name: 'app_stock_adjust', methods: ['POST'])]
        public function adjustStock(
            Inventory $inventory,
            AssignedItemsRepository $assignedRepo,
            EntityManagerInterface $em,
            string $field,
            string $action
        ): Response {
            $assignedCount = $assignedRepo->count(['inventoryId' => $inventory]);

            $success = false;
            if ($action === 'increase') {
                $success = $inventory->increase($field, $assignedCount);
            } elseif ($action === 'decrease') {
                $success = $inventory->decrease($field, $assignedCount);
            }

            if ($success) {
                $em->flush();
                $this->addFlash('success', ucfirst($field) . " updated.");
            } else {
                $this->addFlash('error', "Cannot update " . $field . " any further.");
            }

            return $this->redirectToRoute('app_home'); // adjust route as needed
        }

        #[Route('/{id}/assigned-items', name: 'app_home_assigned_items')]
        public function assignedItems(
            Inventory $inventory,
            AssignedItemsRepository $assignedItemsRepo,
            PaginationService $paginationService,
            Request $request
        ): Response {

            $pagination = $paginationService->paginate(
                $assignedItemsRepo->findByInventoryQB($inventory->getId()), // FIXED
                $request->query->getInt('page', 1),
                10
            );

            return $this->render('home/assigned.html.twig', [
                'inventory' => $inventory,
                'assignedItems' => $pagination['items'],
                'pagination' => $pagination,
            ]);
        }

        #[Route('/{id}/assign-to-user', name: 'app_assign_to_user')]
        public function assignToUser(
            int $id,
            Request $request,
            EntityManagerInterface $em,
            InventoryRepository $inventoryRepo
        ): Response {
            $assignedItem = new AssignedItems();
            $assignedItem->setAssignedAt(new \DateTimeImmutable());

            $inventory = $inventoryRepo->find($id);

            if (!$inventory) {
                throw $this->createNotFoundException("Inventory not found.");
            }

            $assignedItem->setInventoryId($inventory);

            $form = $this->createForm(AssignUserType::class, $assignedItem);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

               if ($form->isSubmitted() && $form->isValid()) {

                    if ($inventory->getAvailable() <= 0) {
                        $form->get('inventoryId')->addError(new FormError('No available devices of this type.'));

                        return $this->render('assigned_items/assign_to_user.html.twig', [
                            'form' => $form->createView(),
                            'inventory' => $inventory,
                        ]);
                    }

                    $inventory->setAvailable($inventory->getAvailable() - 1);
                    $em->persist($assignedItem);
                    $em->flush();

                    $this->addFlash('success', 'Inventory assigned to user successfully.');
                    return $this->redirectToRoute('app_home');
                }
            }

            return $this->render('assigned_items/assign_to_user.html.twig', [
                'form' => $form->createView(),
                'inventory' => $inventory,
            ]);
        }
        
}
