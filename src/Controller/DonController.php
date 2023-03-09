<?php

namespace App\Controller;

use App\Entity\Don;
use App\Entity\Association;
use App\Form\DonType;
use App\Repository\DonRepository;
use App\Repository\MembreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Dompdf\Dompdf;
use Dompdf\Options;


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
    public function new(Request $request, MailerInterface $mailer, DonRepository $donRepository, MembreRepository $MembreRepository, Association $association): Response
    {
        $session = $request->getSession();
        $membre = $session->get('user');
        $membre = $MembreRepository->find($membre->getId());
        $don = new Don();
        $don->setAssociation($association);
        $don->setMembre($membre);
        $form = $this->createForm(DonType::class, $don);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $donRepository->save($don, true);
            $transport = Transport::fromDsn('smtp://donmail.aura@gmail.com:hfuyelyepzpngkwm@smtp.gmail.com:587');
            $mailer = new Mailer($transport);
            $html = $this->renderView('emails/donation.html.twig', [
                'don' => $don,
            ]);
            $email = (new Email())
                ->from('donmail.aura@gmail.com')
                ->to($don->getEmail())
                ->subject('Thank you for your donation!')
                ->html($html);
            $mailer->send($email);

            return $this->redirectToRoute('app_don_thanks', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('don/new.html.twig', [
            'don' => $don,
            'form' => $form,
            'user' => $membre
        ]);
    }

    #[Route('/thanks', name: 'app_don_thanks')]
    public function thanks(Request $request): Response
    {
        $session = $request->getSession();
        $membre = $session->get('user');
        $this->addFlash('success', 'Transaction Done Successfully!');
        return $this->render('don/thanks.html.twig', [
            'user' => $membre
        ]);
    }

    #[Route('/{id}', name: 'app_don_show', methods: ['GET'])]
    public function show(Request $request, Don $don): Response
    {
        $session = $request->getSession();
        $membre = $session->get('user');
        return $this->render('don/show.html.twig', [
            'don' => $don,
            'user' => $membre
        ]);
    }

    #[Route('/{id}/edit', name: 'app_don_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Don $don, DonRepository $donRepository): Response
    {
        $session = $request->getSession();
        $membre = $session->get('user');
        $form = $this->createForm(DonType::class, $don);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $donRepository->save($don, true);

            return $this->redirectToRoute('app_don_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('don/edit.html.twig', [
            'don' => $don,
            'form' => $form,
            'user' => $membre
        ]);
    }

    #[Route('/{id}', name: 'app_don_delete', methods: ['POST'])]
    public function delete(Request $request, Don $don, DonRepository $donRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $don->getId(), $request->request->get('_token'))) {
            $donRepository->remove($don, true);
        }

        return $this->redirectToRoute('app_don_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/pdf', name: 'app_don_pdf', methods: ['GET'])]
    public function pdf(Request $request, Don $don): Response
    {
        $session = $request->getSession();
        $membre = $session->get('user');

        // create PDF options
        $options = new Options();
        $options->set('defaultFont', 'Helvetica');

        // create PDF instance
        $dompdf = new Dompdf($options);

        // generate PDF content
        $html = $this->renderView('don/pdf.html.twig', [
            'don' => $don,
            'user' => $membre,
        ]);

        // load HTML into PDF
        $dompdf->loadHtml($html);

        // render PDF
        $dompdf->render();

        // output PDF to the browser
        $response = new Response();
        $response->headers->set('Content-Type', 'application/pdf');
        $response->setContent($dompdf->output());
        return $response;
    }


    #[Route('/stats/association', name: 'app_don_stats', methods: ['GET'])]
    public function stats(DonRepository $donRepository): Response
    {
        $stats = $donRepository->countDonationsByAssociation();

        return $this->render('don/stats.html.twig', [
            'stats' => $stats,
        ]);
    }
}