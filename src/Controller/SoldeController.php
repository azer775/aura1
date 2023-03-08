<?php

namespace App\Controller;

use App\Entity\Solde;
use App\Entity\Terrain;

use App\Form\SoldeType;
use App\Repository\SoldeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;


#[Route('/solde')]
class SoldeController extends AbstractController
{
    #[Route('/', name: 'app_part_index', methods: ['GET'])]
    public function index(SoldeRepository $soldeRepository): Response
    {
        return $this->render('part/index.html.twig', [
            'parts' => $soldeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_part_new', methods: ['GET', 'POST'])]
    public function new(Request $request, SoldeRepository $soldeRepository): Response
    {
        $solde = new Solde();
        $form = $this->createForm(SoldeType::class, $solde);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $soldeRepository->save($solde, true);

            return $this->redirectToRoute('app_part_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('part/new.html.twig', [
            'part' => $solde,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_part_show', methods: ['GET'])]
    public function show(Solde $solde): Response
    {
        return $this->render('part/show.html.twig', [
            'part' => $solde,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_part_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Solde $solde, SoldeRepository $soldeRepository): Response
    {
        $form = $this->createForm(SoldeType::class, $solde);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $soldeRepository->save($solde, true);

            return $this->redirectToRoute('app_part_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('part/edit.html.twig', [
            'part' => $solde,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_part_delete', methods: ['POST'])]
    public function delete(Request $request, Solde $solde, SoldeRepository $soldeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$solde->getId(), $request->request->get('_token'))) {
            $soldeRepository->remove($solde, true);
        }

        return $this->redirectToRoute('app_part_index', [], Response::HTTP_SEE_OTHER);
    }





    #[Route('/api/soldeAPI', name: 'soldeAPI')]
    public function soldeAPI(Request $request,NormalizerInterface $normalizer): Response
    {

        $em = $this->getDoctrine()->getManager()->getRepository(Solde::class); // ENTITY MANAGER ELY FIH FONCTIONS PREDIFINES

        $soldes = $em->findAll(); // Select * from solde;
        $jsonContent =$normalizer->normalize($soldes, 'json' ,['groups'=>'post:read']);
        return new Response(json_encode($jsonContent));
    }

    #[Route('/api/addSoldeAPI', name: 'addSoldeAPI')]
    public function addSoldeAPI(NormalizerInterface $Normalizer,Request $request): Response
    {
        $solde = new Solde();
        $em = $this->getDoctrine()->getManager();
        $solde->setMontant($request->get('montant'));
        $terrain = $em->getRepository(Terrain::class)->find($request->get('terrain'));
        $solde->setDate(new \DateTime($request->get('date')));
        $solde->setIdTerrain($terrain);
        $em->persist($solde);
        $em->flush();
            $jsonContent = $Normalizer->normalize($solde, 'json',['groups'=>'post:read']);
            return new Response(json_encode($jsonContent));

    }

    #[Route('/api/editSoldeAPI/{id}', name: 'editSoldeAPI')]
    public function editSoldeAPI ($id,Request $request,  NormalizerInterface $normalizer ): Response
    {   
        $em = $this->getDoctrine()->getManager();
        $Solde = $em->getRepository(Solde::class)->find($id);
        $Solde->setMontant($request->get('montant'));
        $terrain = $em->getRepository(Terrain::class)->find($request->get('terrain'));
        $Solde->setDate(new \DateTime($request->get('date')));
        $Solde->setIdTerrain($terrain);
        $em->persist($Solde);
        $em->flush();
        $jsonContent =$normalizer->normalize($Solde, 'json' ,['groups'=>'post:read']);
        return new Response("information updated successfully". json_encode($jsonContent));

    }

    #[Route('/api/deleteSoldeApi/{id}', name: 'deleteSoldeApi')]
    public function deleteSoldeApi(Request $request,NormalizerInterface $normalizer,$id): Response
    {

        $em = $this->getDoctrine()->getManager(); // ENTITY MANAGER ELY FIH FONCTIONS PREDIFINES

        $Solde = $this->getDoctrine()->getManager()->getRepository(Solde::class)->find($id); // ENTITY MANAGER ELY FIH FONCTIONS PREDIFINES

            $em->remove($Solde);
            $em->flush();
            $jsonContent =$normalizer->normalize($Solde, 'json' ,['groups'=>'post:read']);
            return new Response("information deleted successfully".json_encode($jsonContent));
    }
}
