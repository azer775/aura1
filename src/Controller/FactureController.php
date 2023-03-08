<?php

namespace App\Controller;

use App\Entity\Facture;
use App\Entity\Membre;
use App\Form\FactureType;
use App\Repository\FactureRepository;
use App\Repository\MembreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
#[Route('/facture')]
class FactureController extends AbstractController
{
    #[Route('/', name: 'app_facture_index', methods: ['GET'])]
    public function index(FactureRepository $factureRepository): Response
    {
        return $this->render('facture/index.html.twig', [
            'factures' => $factureRepository->findAll(),
        ]);
    }
    #[Route('/ajouter', name: 'app_facture_ajouter', methods: ['GET', 'POST'])]
    public function ajouter(Request $request, FactureRepository $factureRepository, MembreRepository $membreRepository): Response
    {   $date = \DateTime::createFromFormat('Y-m-d', date('Y-m-d'));
        $facture = new Facture();
        $session= $request->getSession();
        $membre=new Membre();
        $membre=$session->get('user');
        $membre=$membreRepository->find($membre->getId());
        $facture->setMembre($membre);
        $facture->setDate($date);
        $panier = $session->get('products', []);
            $somme=0;
            foreach($panier as $p){
                        $somme+=$p['produit']->getPrix() * $p['quantite'];
                
            }
        $facture->setMontant($somme);
        $factureRepository->save($facture,true);
        $message="bonjour Mr/mme ";
        $message .=$membre->getNom().' '.$membre->getPrenom();
        $message .=" votre achat fait le ".$facture->getDate()->format('Y-m-d')."et de montant ".  $facture->getMontant() ." est confirmé";
        $transport = Transport::fromDsn('smtp://donmail.aura@gmail.com:hfuyelyepzpngkwm@smtp.gmail.com:587');
            $mailer = new Mailer($transport);
            $email = (new Email())
                ->from('aura.donation@gmail.com')
                ->to($membre->getEmail())
                ->subject('Thank you for your donation!')
                ->html('<p style="color: #000000; background-color: #0073ff; width: 500px; padding: 16px 0; text-align: center; border-radius: 50px;">' . $message . '  !</p>');
            /*
                // Set HTML "Body"
                $email->html('
                    <h1 style="color: #fff300; background-color: #0073ff; width: 500px; padding: 16px 0; text-align: center; border-radius: 50px;">
                    The HTML version of the message.
                    </h1>
                    <img src="cid:Image_Name_1" style="width: 600px; border-radius: 50px">
                    <br>
                    <img src="cid:Image_Name_2" style="width: 600px; border-radius: 50px">
                    <h1 style="color: #ff0000; background-color: #5bff9c; width: 500px; padding: 16px 0; text-align: center; border-radius: 50px;">
                    The End!
                    </h1>
                ');

                // Add an "Attachment"
                $email->attachFromPath('example_1.txt');
                $email->attachFromPath('example_2.txt');

                // Add an "Image"
                $email->embed(fopen('image_1.png', 'r'), 'Image_Name_1');
                $email->embed(fopen('image_2.jpg', 'r'), 'Image_Name_2');
                */
            $mailer->send($email);

            return $this->redirectToRoute('app_achat_ajouter', [], Response::HTTP_SEE_OTHER);
        

        
    }

    #[Route('/new', name: 'app_facture_new', methods: ['GET', 'POST'])]
    public function new(Request $request, FactureRepository $factureRepository): Response
    {
        $facture = new Facture();
        $form = $this->createForm(FactureType::class, $facture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $factureRepository->save($facture, true);
            
            return $this->redirectToRoute('app_facture_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('facture/new.html.twig', [
            'facture' => $facture,
            'form' => $form,
        ]);
    }
    #[Route('/afficher', name: 'app_facture_afficher', methods: ['GET'])]
    public function afficher(Request $request, FactureRepository $factureRepository): Response
    {    $session= $request->getSession();
        $membre=new Membre();
        $membre=$session->get('user');
        $factures=$factureRepository->getAchatsPourfacture($membre->getId());
        return $this->render('facture/afficher.html.twig', [
            'factures' => $factures,
            'user' => $membre
        ]);
    }
    #[Route('/{id}', name: 'app_facture_show', methods: ['GET'])]
    public function show(Facture $facture): Response
    {   
        
        return $this->render('facture/show.html.twig', [
            'facture' => $facture,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_facture_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Facture $facture, FactureRepository $factureRepository): Response
    {
        $form = $this->createForm(FactureType::class, $facture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $factureRepository->save($facture, true);

            return $this->redirectToRoute('app_facture_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('facture/edit.html.twig', [
            'facture' => $facture,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_facture_delete', methods: ['POST'])]
    public function delete(Request $request, Facture $facture, FactureRepository $factureRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$facture->getId(), $request->request->get('_token'))) {
            $factureRepository->remove($facture, true);
        }

        return $this->redirectToRoute('app_facture_index', [], Response::HTTP_SEE_OTHER);
    }
}

        

        
            

           

       
    