<?php

namespace App\Controller;

use App\Entity\Affectations;
use App\Form\AffectationsType;
use App\Repository\AffectationsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/affectations')]
class AffectationsController extends AbstractController
{
    #[Route('/', name: 'app_affectations_index', methods: ['GET'])]
    public function index(AffectationsRepository $affectationsRepository): Response
    {
        return $this->render('affectations/index.html.twig', [
            'affectations' => $affectationsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_affectations_new', methods: ['GET', 'POST'])]
    public function new(Request $request, AffectationsRepository $affectationsRepository): Response
    {
        $affectation = new Affectations();
        $form = $this->createForm(AffectationsType::class, $affectation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $affectationsRepository->save($affectation, true);

            return $this->redirectToRoute('app_affectations_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('affectations/new.html.twig', [
            'affectation' => $affectation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_affectations_show', methods: ['GET'])]
    public function show(Affectations $affectation): Response
    {
        return $this->render('affectations/show.html.twig', [
            'affectation' => $affectation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_affectations_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Affectations $affectation, AffectationsRepository $affectationsRepository): Response
    {
        $form = $this->createForm(AffectationsType::class, $affectation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $affectationsRepository->save($affectation, true);

            return $this->redirectToRoute('app_affectations_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('affectations/edit.html.twig', [
            'affectation' => $affectation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_affectations_delete', methods: ['POST'])]
    public function delete(Request $request, Affectations $affectation, AffectationsRepository $affectationsRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$affectation->getId(), $request->request->get('_token'))) {
            $affectationsRepository->remove($affectation, true);
        }

        return $this->redirectToRoute('app_affectations_index', [], Response::HTTP_SEE_OTHER);
    }
}
