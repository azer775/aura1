<?php

namespace App\Controller;

use App\Entity\FraisEnergie;
use App\Form\FraisEnergieType;
use App\Repository\FraisEnergieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/frais/energie')]
class FraisEnergieController extends AbstractController
{
    #[Route('/', name: 'app_frais_energie_index', methods: ['GET'])]
    public function index(FraisEnergieRepository $fraisEnergieRepository): Response
    {
        return $this->render('frais_energie/index.html.twig', [
            'frais_energies' => $fraisEnergieRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_frais_energie_new', methods: ['GET', 'POST'])]
    public function new(Request $request, FraisEnergieRepository $fraisEnergieRepository): Response
    {
        $fraisEnergie = new FraisEnergie();
        $form = $this->createForm(FraisEnergieType::class, $fraisEnergie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $fraisEnergieRepository->save($fraisEnergie, true);

            return $this->redirectToRoute('app_frais_energie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('frais_energie/new.html.twig', [
            'frais_energie' => $fraisEnergie,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_frais_energie_show', methods: ['GET'])]
    public function show(FraisEnergie $fraisEnergie): Response
    {
        return $this->render('frais_energie/show.html.twig', [
            'frais_energie' => $fraisEnergie,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_frais_energie_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, FraisEnergie $fraisEnergie, FraisEnergieRepository $fraisEnergieRepository): Response
    {
        $form = $this->createForm(FraisEnergieType::class, $fraisEnergie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $fraisEnergieRepository->save($fraisEnergie, true);

            return $this->redirectToRoute('app_frais_energie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('frais_energie/edit.html.twig', [
            'frais_energie' => $fraisEnergie,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_frais_energie_delete', methods: ['POST'])]
    public function delete(Request $request, FraisEnergie $fraisEnergie, FraisEnergieRepository $fraisEnergieRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$fraisEnergie->getId(), $request->request->get('_token'))) {
            $fraisEnergieRepository->remove($fraisEnergie, true);
        }

        return $this->redirectToRoute('app_frais_energie_index', [], Response::HTTP_SEE_OTHER);
    }
}
