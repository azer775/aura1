<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
//use Symfony\Component\Messenger\Transport\Serialization\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/produit')]
class ProduitController extends AbstractController
{
    #[Route('/', name: 'app_produit_index', methods: ['GET'])]
    public function index(ProduitRepository $produitRepository,CategorieRepository $categorieRepository,Request $request, PaginatorInterface $paginator): Response
    {   $session= $request->getSession();
        $membre=$session->get('user');
        $produits=$produitRepository->findAll();
        $produits=$paginator->paginate(
            $produits, /* query NOT result */
            $request->query->getInt('page', 1),
            2
        );
        return $this->render('produit/index.html.twig', [
            'produits' => $produits,
            'categories' => $categorieRepository->findAll(),
            'user' => $membre
            
        ]);
        return $this->render('produit/afficher.html.twig', [
            'produits' => $produitRepository->findAll(),
            'categories' => $categorieRepository->findAll(),
            
        ]);
        
    }
    #[Route('/search', name: 'produit_search', methods: ['GET'])]
    public function search(Request $request,ProduitRepository $produitRepository): JsonResponse
    {
        $term = $request->query->get('term');

        $produits = $produitRepository->findAll();

        $results = [];
        foreach ($produits as $produit) {
            $results[] = [
                'id' => $produit->getId(),
                'nom' => $produit->getNomProd(),
                'description' => $produit->getDescription(),
                // ...
            ];
        }

        return new JsonResponse($results);
    }
    #[Route('/recherche', name: 'produit_recherche')]
    public function recherche(): Response
    {
        return $this->render('produit/recherche.html.twig');
    }
    #[Route('/afficher', name: 'app_produit_afficher', methods: ['GET'])]
    public function afficher(ProduitRepository $produitRepository,CategorieRepository $categorieRepository): Response
    {
        return $this->render('produit/afficher.html.twig', [
            'produits' => $produitRepository->findAll(),
            'categories' => $categorieRepository->findAll(),
            
        ]);
        
    }
    #[Route('/list', name: 'app_produit_list', methods: ['GET'])]
    public function list(ProduitRepository $repo,SerializerInterface $serializerInterface)
    {$produits=$repo->findAll();
        $json=$serializerInterface->serialize($produits,'json',['groups'=>'produit']);
        dump($json);
       die; 
        
    }
    #[Route('/addp', name: 'app_produit_add')]
    public function add(ProduitRepository $repo,SerializerInterface $serializerInterface,Request $request,EntityManagerInterface $em)
    {$content=$request->getContent();
        $data=$serializerInterface->deserialize($content,Produit::class,'json');
        $em->persist($data);
        $em->flush();
        return new Response("success");
    }
    #[Route('/new', name: 'app_produit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ProduitRepository $produitRepository): Response
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $produitRepository->save($produit, true);

            return $this->redirectToRoute('app_produit_afficher', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('produit/new.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_produit_show', methods: ['GET'])]
    public function show(Produit $produit,Request $request): Response
    {
        $session= $request->getSession();
        $membre=$session->get('user');
        return $this->render('produit/show.html.twig', [
            'produit' => $produit,
            'user' => $membre
        ]);
    }

    #[Route('/{id}/edit', name: 'app_produit_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Produit $produit, ProduitRepository $produitRepository): Response
    {
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $produitRepository->save($produit, true);

            return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('produit/edit.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_produit_delete', methods: ['POST'])]
    public function delete(Request $request, Produit $produit, ProduitRepository $produitRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$produit->getId(), $request->request->get('_token'))) {
            $produitRepository->remove($produit, true);
        }

        return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
    }
}
