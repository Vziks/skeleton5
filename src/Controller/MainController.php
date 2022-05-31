<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route(path: "/", name: "app_main", methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }

    #[Route(path: "/folder/", name: "app_folder", methods: ['GET'])]
    public function folder(): Response
    {
        return $this->render('main/index.html.twig', [
            'controller_name' => 'FolderController',
        ]);
    }
}
