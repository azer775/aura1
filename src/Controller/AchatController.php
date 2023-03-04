<?php

namespace App\Controller;

use App\Entity\Achat;
use App\Entity\Membre;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;
use App\Entity\Facture;
use App\Form\AchatType;
use App\Repository\AchatRepository;
use App\Repository\CategorieRepository;
use App\Repository\MembreRepository;
use App\Repository\ProduitRepository;
use App\Repository\FactureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/achat')]
class AchatController extends AbstractController
{
    #[Route('/', name: 'app_achat_index', methods: ['GET'])]
    public function index(AchatRepository $achatRepository,CategorieRepository $categorieRepository): Response
    {   
        $categories=$categorieRepository->findAll();
        foreach($categories as $c)
        {
            $catnom [] =$c->getNom();
            $nbr [] = $achatRepository->getNombreAchatsPourCategorie($c->getId());
        }
        return $this->render('achat/index.html.twig', [
            'achats' => $achatRepository->findAll(),
            'catnom' => json_encode($catnom),
            'nbr' => json_encode($nbr),
        ]);
    }
    
    #[Route('/ajouter', name: 'app_achat_ajouter', methods: ['GET', 'POST'])]
    public function ajouter(Request $request, AchatRepository $achatRepository, FactureRepository $factureRepository,MembreRepository $membreRepository,ProduitRepository $produitRepository): Response
    {
        $achat = new Achat();
        $facture =new Facture();
        $facture=$factureRepository->findOneBy([],['id'=> 'DESC'],1);
        $session= $request->getSession();
        $membre=new Membre();
        $membre=$session->get('user');
        $membre=$membreRepository->find($membre->getId());
        $panier=$session->get('products',[]);

        foreach($panier as $p)
        {   $achat->setNbrPiece($p['quantite']);
            $achat->setPrix($p['produit']->getPrix() * $p['quantite']);
            $achat->setFacture($facture);
            $achat->setMembre($membre);
            $achat->setProduit($produitRepository->find($p['produit']->getId()));
            $achatRepository->save($achat,true);

         }
         return $this->redirectToRoute('app_achat_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/new', name: 'app_achat_new', methods: ['GET', 'POST'])]
    public function new(Request $request, AchatRepository $achatRepository): Response
    {
        $achat = new Achat();
        $form = $this->createForm(AchatType::class, $achat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $achatRepository->save($achat, true);

            return $this->redirectToRoute('app_achat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('achat/new.html.twig', [
            'achat' => $achat,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_achat_show', methods: ['GET'])]
    public function show(Achat $achat): Response
    {
        return $this->render('achat/show.html.twig', [
            'achat' => $achat,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_achat_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Achat $achat, AchatRepository $achatRepository): Response
    {
        $form = $this->createForm(AchatType::class, $achat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $achatRepository->save($achat, true);

            return $this->redirectToRoute('app_achat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('achat/edit.html.twig', [
            'achat' => $achat,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_achat_delete', methods: ['POST'])]
    public function delete(Request $request, Achat $achat, AchatRepository $achatRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$achat->getId(), $request->request->get('_token'))) {
            $achatRepository->remove($achat, true);
        }

        return $this->redirectToRoute('app_achat_index', [], Response::HTTP_SEE_OTHER);
    }
}
