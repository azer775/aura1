<?php

namespace App\Controller;
use App\Entity\Produit;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
#[Route('/panier')]
class PanierController extends AbstractController
{   #[Route('/ajouter/{id}', name: 'panier_ajouter')]
        public function ajouterpanier(Request $request, Produit $produit): Response
        {   $session= $request->getSession();

                $products = $session->get('products', []);
                $id=$produit->getId();
                if(isset($products[$id])){
                $products[$id]['quantite']++;
                }else{
                $products[$id] = [
                'produit' => $produit,
                'quantite' => 1,
                ];
                }
                $session->set('products', $products);

                return $this->redirectToRoute('panier_afficher');
        }
        #[Route('/dim/{id}', name: 'panier_dim', methods: ['GET', 'POST'])]
        public function dimpanier(Request $request, Produit $produit): Response
        {  
             $session= $request->getSession();

                $products = $session->get('products', []);
                $id=$produit->getId();
                if(isset($products[$id])){
                    if($products[$id]['quantite']>1)
                        {$products[$id]['quantite']--;}
                    else{
                        unset($products[$id]);
                    }
                }
                $session->set('products', $products);

                return $this->redirectToRoute('panier_afficher');
        }
        #[Route('/supprimer/{id}', name: 'panier_supprimer')]
        public function supprimerpanier(Request $request, int $id): Response
        {   
            $session= $request->getSession();

                $products = $session->get('products', []);
                //$id=$produit->getId();
                if(isset($products[$id])){
                unset($products[$id]);
                }
                $session->set('products', $products);

                return $this->redirectToRoute('panier_afficher');
        }
    #[Route('/', name: 'panier_afficher')]
        public function afficherPanier(Request $request)
        {   $session= $request->getSession();
            $membre=$session->get('user');
            $panier = $session->get('products', []);
            $somme=0;
            foreach($panier as $p){
                        $somme+=$p['produit']->getPrix() * $p['quantite'];
                
            }
            return $this->render('panier/index.html.twig', [
                'produits' => $panier,
                'user' => $membre,
                'somme' => $somme
            ]);
        }
}
