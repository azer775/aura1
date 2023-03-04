<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(Request $request): Response
    {   $session= $request->getSession();
        $membre=$session->get('user');
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'user' => $membre
        ]);
    }
    public function inde(Request $request): Response
    {   $session= $request->getSession();
        $membre=$session->get('user');
        return $this->render('base.html.twig', [
            'controller_name' => 'HomeController',
            'user' => $membre
        ]);
    }
}
