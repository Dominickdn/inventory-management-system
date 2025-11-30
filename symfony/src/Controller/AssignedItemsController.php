<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AssignedItemsController extends AbstractController
{
    #[Route('/assigned', name: 'app_assigned_items')]
    public function index(): Response
    {
        return $this->render('assigned_items/index.html.twig', [
            'controller_name' => 'AssignedItemsController',
        ]);
    }
}
