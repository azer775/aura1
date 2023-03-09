<?php

namespace App\Controller;

use App\Entity\Association;
use App\Form\AssociationType;
use App\Repository\AssociationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/association')]
class AssociationController extends AbstractController
{
    #[Route('/', name: 'app_association_index', methods: ['GET'])]
    public function index(Request $request, AssociationRepository $associationRepository, PaginatorInterface $paginator): Response
    {
        $session = $request->getSession();
        $membre = $session->get('user');
        $searchQuery = $request->query->get('q');
        $associations = $searchQuery ? $associationRepository->search($searchQuery) : $associationRepository->findAll();
        $associations=$paginator->paginate(
            $associations, /* query NOT result */
            $request->query->getInt('page', 1),
            2
        );
        $association =$associationRepository->findAll();
        $association=$paginator->paginate(
            $association, /* query NOT result */
            $request->query->getInt('page', 1),
            2
        );
        return $this->render('association/index.html.twig', [
            'associations' => $association,
            'associations' => $associations,
            'user' => $membre
        ]);
    }
    #[Route('/afficher', name: 'app_association_afficher', methods: ['GET'])]
    public function afficher(AssociationRepository $associationRepository): Response
    {
        return $this->render('association/afficher.html.twig', [
            'associations' => $associationRepository->findAll(),
        ]);
    }
    #[Route('/new', name: 'app_association_new', methods: ['GET', 'POST'])]
    public function new(Request $request, AssociationRepository $associationRepository): Response
    {
        $association = new Association();
        $form = $this->createForm(AssociationType::class, $association);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $associationRepository->save($association, true);

            return $this->redirectToRoute('app_association_afficher', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('association/new.html.twig', [
            'association' => $association,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_association_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Association $association, AssociationRepository $associationRepository): Response
    {
        $form = $this->createForm(AssociationType::class, $association);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $associationRepository->save($association, true);

            return $this->redirectToRoute('app_association_afficher', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('association/edit.html.twig', [
            'association' => $association,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_association_delete', methods: ['POST'])]
    public function delete(Request $request, Association $association, AssociationRepository $associationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $association->getId(), $request->request->get('_token'))) {
            $associationRepository->remove($association, true);
        }

        return $this->redirectToRoute('app_association_afficher', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}', name: 'app_association_show', methods: ['GET'])]
    public function show(Association $association): Response
    {
        return $this->render('association/show.html.twig', [
            'association' => $association,
        ]);
    }

    #[Route('/export/xls', name: 'app_association_export_csv')]
    public function exportCsv(AssociationRepository $associationRepository): StreamedResponse
    {
        $associations = $associationRepository->findAll();

        $response = new StreamedResponse();
        $response->setCallback(function () use ($associations) {
            $handle = fopen('php://output', 'w+');
            fputcsv($handle, ['Nom', 'Adresse', 'RIB', 'Email']);

            foreach ($associations as $association) {
                fputcsv($handle, [
                    $association->getNom(),
                    $association->getAdresse(),
                    $association->getRib(),
                    $association->getEmail(),
                ]);
            }

            fclose($handle);
        });

        $response->setStatusCode(Response::HTTP_OK);
        $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment; filename=associations.csv');
        $response->headers->set('Expires', '0');
        $response->headers->set('Cache-Control', 'must-revalidate, post-check=0, pre-check=0');
        $response->headers->set('Content-Description', 'File Transfer');
        $response->headers->set('Content-Transfer-Encoding', 'binary');
        $response->headers->set('Pragma', 'public');

        return $response;
    }

    #[Route('/', name: 'app_association_search', methods: ['GET'])]
    public function search(Request $request, AssociationRepository $associationRepository): Response
    {
        $query = $request->query->get('q');
        $associations = $associationRepository->search($query);

        return $this->render('association/_search_results.html.twig', [
            'associations' => $associations,
        ]);
    }

    #[Route('/', name: 'reset_associations', methods: ['GET'])]
    public function reset(AssociationRepository $associationRepository)
    {
        $associations = $associationRepository->reset();

        return $this->render('association/index.html.twig', [
            'associations' => $associations,
        ]);
    }
}