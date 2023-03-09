<?php

namespace App\Controller;

use App\Entity\Membre;
use App\Form\MembreType;
use App\Form\LoginType;
use App\Form\LoginFType;
use App\Repository\MembreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManager;

#[Route('/membre')]
class MembreController extends AbstractController
{
    #[Route('/', name: 'app_membre_index', methods: ['GET'])]
    public function index(Request $request,MembreRepository $membreRepository): Response
    {   $session= $request->getSession();
        return $this->render('membre/index.html.twig', [
            'membres' => $membreRepository->findAll(),
            'membr' => $session->get('user', [])
        ]);
    }
    #[Route('/login', name: 'app_membre_login')]
    public function login(Request $request, MembreRepository $membreRepository): Response
    {   
        $form = $this->createForm(LoginType::class);

        $form->handleRequest($request);
        $session= $request->getSession();
        if ($form->isSubmitted() && $form->isValid()) {
            
            $email = $form->get('email')->getData();
            $password = $form->get('password')->getData();
            $membre=$membreRepository->verif($email,$password);
           
                if ($membre!=null) {
                    $session->set('user', $membre);
                    return $this->redirectToRoute('app_membre_index', [], Response::HTTP_SEE_OTHER);
                } 
                else {
                    $this->addFlash('error', 'Invalid credentials');
                }
        }
        return $this->render('membre/login.html.twig', [
            'forma' => $form->createView()
        ]); 
    }
    #[Route('/loginf', name: 'app_membre_loginf')]
    public function loginf(Request $request, MembreRepository $membreRepository): Response
    {   
        $form = $this->createForm(LoginFType::class);

        $form->handleRequest($request);
        $session= $request->getSession();
        if ($form->isSubmitted() && $form->isValid()) {
            
            $email = $form->get('email')->getData();
            $password = $form->get('password')->getData();
            $membre=$membreRepository->verif($email,$password);
           
                if ($membre!=null) {
                    $session->set('user', $membre);
                    return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
                } 
                else {
                    $this->addFlash('error', 'Invalid credentials');
                }
        }
        return $this->render('membre/loginF.html.twig', [
            'forma' => $form->createView()
        ]); 
    }
    #[Route('/signup', name: 'app_membre_signup')]
    public function signup(Request $request, MembreRepository $membreRepository): Response
    {   $membre = new Membre();
        $form = $this->createForm(MembreType::class,$membre);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $email = $form->get('email')->getData();
            
           
                if ($membreRepository->findOneBy(['email' => $email])==null) {
                    $membre->setRole("membre");
                    $membreRepository->save($membre, true);
                    return $this->redirectToRoute('app_membre_loginf', [], Response::HTTP_SEE_OTHER);
                } 
                else {
                    $this->addFlash('error', 'Invalid credentials');
                }
        }
        return $this->render('membre/signup.html.twig', [
            'form' => $form->createView()
        ]); 
    }
    #[Route('/signuppar', name: 'app_membre_signuppar')]
    public function signuppar(Request $request, MembreRepository $membreRepository): Response
    {   $membre = new Membre();
        $form = $this->createForm(MembreType::class,$membre);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $email = $form->get('email')->getData();
            
           
                if ($membreRepository->findOneBy(['email' => $email])==null) {
                    $membre->setRole("partenaire");
                    $membreRepository->save($membre, true);
                    return $this->redirectToRoute('app_membre_loginf', [], Response::HTTP_SEE_OTHER);
                } 
                else {
                    $this->addFlash('error', 'Invalid credentials');
                }
        }
        return $this->render('membre/signup.html.twig', [
            'form' => $form->createView()
        ]); 
    }
    #[Route('/new', name: 'app_membre_new', methods: ['GET', 'POST'])]
    public function new(Request $request, MembreRepository $membreRepository): Response
    {
        $membre = new Membre();
        $form = $this->createForm(MembreType::class, $membre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $membre->setRole("Admin");
            $membreRepository->save($membre, true);

            return $this->redirectToRoute('app_membre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('membre/new.html.twig', [
            'membre' => $membre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_membre_show', methods: ['GET'])]
    public function show(Membre $membre): Response
    {
        return $this->render('membre/show.html.twig', [
            'membre' => $membre,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_membre_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Membre $membre, MembreRepository $membreRepository): Response
    {
        $form = $this->createForm(MembreType::class, $membre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $membreRepository->save($membre, true);

            return $this->redirectToRoute('app_membre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('membre/edit.html.twig', [
            'membre' => $membre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_membre_delete', methods: ['POST'])]
    public function delete(Request $request, Membre $membre, MembreRepository $membreRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$membre->getId(), $request->request->get('_token'))) {
            $membreRepository->remove($membre, true);
        }

        return $this->redirectToRoute('app_membre_index', [], Response::HTTP_SEE_OTHER);
    }
   
    
}
