<?php

namespace App\Controller;

use App\Repository\AssignedItemsRepository;
use App\Repository\InventoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AssignedItemsController extends AbstractController
{

    #[Route('/assigned', name: 'app_assigned_items')]
    public function manageInventory(
        InventoryRepository $inventoryRepo,
        AssignedItemsRepository $assignedItemsRepo
    ): Response {

        // Available inventory (at least 1 available)
        $availableInventory = $inventoryRepo->createQueryBuilder('i')
            ->andWhere('i.available >= 1')
            ->getQuery()
            ->getResult();

        // Assigned items (assigned items table)
        $assignedItems = $assignedItemsRepo->findAll();

        return $this->render('assigned_items/index.html.twig', [
            'availableInventory' => $availableInventory,
            'assignedItems' => $assignedItems,
        ]);
    }
}
