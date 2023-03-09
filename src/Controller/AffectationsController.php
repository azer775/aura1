<?php

namespace App\Controller;

use App\Entity\Affectations;
use App\Form\AffectationsType;
use App\Repository\AffectationsRepository;
use App\Repository\TechnicienRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/affectations')]
class AffectationsController extends AbstractController
{
    #[Route('/', name: 'app_affectations_index', methods: ['GET'])]
    public function index(Request $request ,AffectationsRepository $affectationsRepository,TechnicienRepository $TechnicienRepository, PaginatorInterface $paginator): Response
    {   
        $techs=$TechnicienRepository->findAll();
        foreach($techs as $t)
        {
            $catnom [] =$t->getNom();
            $nbr [] = $affectationsRepository->countAffectationsForTechnicien($t->getId());
        }
        $affectations=$affectationsRepository->findAll();
        $affectations=$paginator->paginate(
            $affectations, /* query NOT result */
            $request->query->getInt('page', 1),
            5
        );
        return $this->render('affectations/index.html.twig', [
            'affectations' =>$affectations,
            'technom' => json_encode($catnom),
            'nbr' => json_encode($nbr)
        ]);
    }
    #[Route('/stat', name: 'app_affectations_stat', methods: ['GET'])]
    public function stat(AffectationsRepository $affectationsRepository,TechnicienRepository $TechnicienRepository): Response
    {   
        $techs=$TechnicienRepository->findAll();
        foreach($techs as $t)
        {
            $catnom [] =$t->getNom();
            $nbr [] = $affectationsRepository->countAffectationsForTechnicien($t->getId());
        }
        $affectations=$affectationsRepository->findAll();

        return $this->render('affectations/stat.html.twig', [
            'affectations' =>$affectations,
            'technom' => json_encode($catnom),
            'nbr' => json_encode($nbr)
        ]);
    }

    #[Route('/calendar', name: 'app_affectations_calendar', methods: ['GET'])]
    public function calendar(AffectationsRepository $affectationsRepository)
    {
        $events = $affectationsRepository->findAll();

        $rdvs = [];

        foreach($events as $event){
            $rdvs[] = [
                'id' => $event->getId(),
                'Technicien' => $event->getTechnicien(),
                'Terrain' => $event->getTerrain(),
            ];
        }

        $data = json_encode($rdvs);

        return $this->render('affectations/calendar.html.twig', compact('data'));
    }

    #[Route('/afficher', name: 'app_affectations_afficher', methods: ['GET'])]
    public function afficher(Request $request ,AffectationsRepository $affectationsRepository, PaginatorInterface $paginator): Response
    {    $session= $request->getSession();
         $membre=$session->get('user');
        $affectations=$affectationsRepository->findByMembre($membre->getId());
        $affectations=$paginator->paginate(
            $affectations, /* query NOT result */
            $request->query->getInt('page', 1),
            5
        );
        return $this->render('affectations/afficher.html.twig', [
            'affectations' => $affectations,
            'user' => $membre
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
    public function show(Request $request,Affectations $affectation): Response
    {   $session= $request->getSession();
        $membre=$session->get('user');
        return $this->render('affectations/show.html.twig', [
            'affectation' => $affectation,
            'user' => $membre
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
