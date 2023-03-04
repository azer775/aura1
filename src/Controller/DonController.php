<?php

namespace App\Controller;

use App\Entity\Don;
use App\Entity\Association;
use App\Entity\Membre;
use App\Form\DonType;
use App\Repository\DonRepository;
use App\Repository\MembreRepository;
use App\Repository\AssociationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/don')]
class DonController extends AbstractController
{
    #[Route('/', name: 'app_don_index', methods: ['GET'])]
    public function index(DonRepository $donRepository): Response
    {
        return $this->render('don/index.html.twig', [
            'dons' => $donRepository->findAll(),
        ]);
    }

    #[Route('/new/{id}', name: 'app_don_new', methods: ['GET', 'POST'])]
    public function new(Request $request, DonRepository $donRepository, Association $Association, MembreRepository $membreRepository ): Response
    {  
        $session= $request->getSession();
        $membre=new Membre();
        $membre=$session->get('user');
        $membre=$membreRepository->find($membre->getId());
        $don = new Don();
        $don->setMembre($membre);
        $don->setAssociation($Association);
        $form = $this->createForm(DonType::class, $don);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $donRepository->save($don, true);

            return $this->redirectToRoute('app_don_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('don/new.html.twig', [
            'don' => $don,
            'form' => $form,
            'user' => $membre
        ]);
    }

    #[Route('/{id}', name: 'app_don_show', methods: ['GET'])]
    public function show(Don $don): Response
    {
        return $this->render('don/show.html.twig', [
            'don' => $don,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_don_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Don $don, DonRepository $donRepository): Response
    {
        $form = $this->createForm(DonType::class, $don);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $donRepository->save($don, true);

            return $this->redirectToRoute('app_don_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('don/edit.html.twig', [
            'don' => $don,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_don_delete', methods: ['POST'])]
    public function delete(Request $request, Don $don, DonRepository $donRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$don->getId(), $request->request->get('_token'))) {
            $donRepository->remove($don, true);
        }

        return $this->redirectToRoute('app_don_index', [], Response::HTTP_SEE_OTHER);
    }
}
