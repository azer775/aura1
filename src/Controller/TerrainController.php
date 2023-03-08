<?php

namespace App\Controller;

use App\Entity\Terrain;
use App\Entity\Partenaire;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Form\TerrainType;
use App\Repository\TerrainRepository;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use Another\Namespace\NamedAddress; 
use App\Service\QrCodeService;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Label\Font\NotoSans;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;


#[Route('/terrain')]
class TerrainController extends AbstractController
{

    #[Route('/', name: 'app_terrain_index', methods: ['GET'])]
    public function index(PaginatorInterface $paginator,TerrainRepository $terrainRepository,Request $request): Response
    {
        $data=$terrainRepository->findAll();
        $terrains=$paginator->paginate(
            $data,
            $request->query->getInt('page',1),
            3

        );
        return $this->render('terrain/index.html.twig', [
            'terrains' => $terrains,
        ]);
    }

    #[Route('/list', name: 'app_terrainFront_index', methods: ['GET'])]
    public function indexFront(PaginatorInterface $paginator,TerrainRepository $terrainRepository,Request $request): Response
    {
        $data=$terrainRepository->findAll();
        $terrains=$paginator->paginate(
            $data,
            $request->query->getInt('page',1),
            3

        );
        return $this->render('terrain/indexFront.html.twig', [
            'terrains' => $terrains,
        ]);
    }

    #[Route('/new', name: 'app_terrain_new', methods: ['GET', 'POST'])]
    public function new(MailerInterface $maileer,Request $request, TerrainRepository $terrainRepository): Response
    {
      
        $terrain = new Terrain();
      //  dump($maileer);
        $form = $this->createForm(TerrainType::class, $terrain);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $terrainRepository->save($terrain, true);
        


            
            $transport = Transport::fromDsn("smtp://pidev.aura@gmail.com:xutqlenntohkygkr@smtp.gmail.com:587?encryption=tls");
            $mailer = new Mailer($transport);
           $emailTo = $terrain->getIdPartenaire()->getEmail() ;
            $email = (new Email())
       
            ->from('pidev.aura@gmail.com')
            ->to($emailTo)
            ->subject('Affectation d un terrain!')
            ->text('Sending emails is fun again!')
            ->html('<p>Bonjour , un terrain vous a été ajouté avec succès!!!</p>');   
        

   
$headers = $email->getHeaders();

$mailer->send($email);

//dd($mailer->send($email)); 
                return $this->redirectToRoute('app_terrain_index', [], Response::HTTP_SEE_OTHER);

        }
      
        return $this->renderForm('terrain/new.html.twig', [
            'terrain' => $terrain,
            'form' => $form
        ]);
    }

    #[Route('/{id}', name: 'app_terrain_show', methods: ['GET'])]
    public function show(Terrain $terrain): Response
    {
        $writer = new PngWriter();
        $qrCode = QrCode::create('addresse du terrain : '. $terrain->getAdresse(). ' surface : ' .$terrain->getSurface() . ' Potentiel : ' . $terrain->getPotentiel()   )
            ->setEncoding(new Encoding('UTF-8'))
            ->setErrorCorrectionLevel(new ErrorCorrectionLevelLow())
            ->setSize(120)
            ->setMargin(0)
            ->setForegroundColor(new Color(0, 0, 0))
            ->setBackgroundColor(new Color(255, 255, 255));
        $logo = null;
        $label = Label::create('')->setFont(new NotoSans(8));
 
        $qrCodes = [];
        $qrCodes['img'] = $writer->write($qrCode, $logo)->getDataUri();
        $qrCodes['simple'] = $writer->write(
                                $qrCode,
                                null,
                                $label->setText('Terrain')
                            )->getDataUri();
 
        $qrCode->setForegroundColor(new Color(255, 0, 0));
        $qrCode->setForegroundColor(new Color(0, 0, 0))->setBackgroundColor(new Color(255, 0, 0));
 
        $qrCode->setSize(200)->setForegroundColor(new Color(0, 0, 0))->setBackgroundColor(new Color(255, 255, 255));
        $qrCodes['withImage'] = $writer->write(
            $qrCode,
            null,
            $label->setText('With Image')->setFont(new NotoSans(10))
        )->getDataUri();

        return $this->render('terrain/show.html.twig', [
            'terrain' => $terrain,
            'qrCodes'=>$qrCodes,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_terrain_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Terrain $terrain, TerrainRepository $terrainRepository): Response
    {
        $form = $this->createForm(TerrainType::class, $terrain);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $terrainRepository->save($terrain, true);

            return $this->redirectToRoute('app_terrain_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('terrain/edit.html.twig', [
            'terrain' => $terrain,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_terrain_delete', methods: ['POST'])]
    public function delete(Request $request, Terrain $terrain, TerrainRepository $terrainRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$terrain->getId(), $request->request->get('_token'))) {
            $terrainRepository->remove($terrain, true);
        }

        return $this->redirectToRoute('app_terrain_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/export/pdf', name: 'pdft', methods: ['GET'])]
    public function pdfd (TerrainRepository $terrainRepository,Request $request): Response
    {
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
//        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

//        $produit = $produitRepository->findAll();

        // Retrieve the HTML generated in our twig file
        $data=$terrainRepository->findAll();
        $html = $this->renderView('terrain/pdf.html.twig',[
            'terrains' => $data,
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("Terrain.pdf", [
            "Attachment" => true
        ]);
        return new Response('', 200, [
            'Content-Type' => 'application/pdf',
        ]);
    }
    #[Route('/search/searcht', name: 'search_terrain', methods: ['GET'])]
    public function searchTerrain(Request $request, PaginatorInterface $paginator)
    {
        /*$t =$this->getDoctrine()->getRepository(Terrain::class)->findBy(['adresse' => $request->get('search')]);
        if ($t == []){
            $t =$this->getDoctrine()->getRepository(Terrain::class)->findBy(['surface' => $request->get('search')]);

        };
        if ($t==[]){
            $t =$this->getDoctrine()->getRepository(Terrain::class)->findBy(['potentiel' => $request->get('search')]);
        }
        */
        $adresse=$request->get('search');
        $surface=$request->get('search2');
        $t =$this->getDoctrine()->getRepository(Terrain::class)->findBy(['adresse' => $adresse,'surface'=>$surface]);
        
        $terrains=$paginator->paginate(
            $t,
            $request->query->getInt('page',1),
            3

        );
        //dump($request->get('search'));
        if (null != $request->get('search')) {
            return $this->render('/terrain/indexFront.html.twig', [
                'terrains' => $terrains
            ]);
        }
        return  $this->redirectToRoute('app_terrain_index');
    }







    #[Route('/api/terrainAPI', name: 'terrainAPI')]
    public function terrainAPI(Request $request,NormalizerInterface $normalizer): Response
    {

        $em = $this->getDoctrine()->getManager()->getRepository(Terrain::class); // ENTITY MANAGER ELY FIH FONCTIONS PREDIFINES

        $terrains = $em->findAll(); // Select * from terrain;
        $jsonContent =$normalizer->normalize($terrains, 'json' ,['groups'=>'post:read']);
        return new Response(json_encode($jsonContent));
    }

    #[Route('/api/addTerrainAPI', name: 'addTerrainAPI')]
    public function addTerrainAPI(NormalizerInterface $Normalizer,Request $request): Response
    {
        $terrain = new Terrain();
        $em = $this->getDoctrine()->getManager();
        $terrain->setSurface($request->get('surface'));
        
        $terrain->setPotentiel($request->get('potentiel'));

        $terrain->setAdresse($request->get('adresse'));
        $em->persist($terrain);
        $em->flush();
            $jsonContent = $Normalizer->normalize($terrain, 'json',['groups'=>'post:read']);
            return new Response(json_encode($jsonContent));

    }

    #[Route('/api/editTerrainAPI/{id}', name: 'editTerrainAPI')]
    public function editTerrainAPI ($id,Request $request , NormalizerInterface $normalizer ): Response
    {   
        $em = $this->getDoctrine()->getManager();
        $terrain = $em->getRepository(Terrain::class)->find($id);
        $terrain->setSurface($request->get('surface'));
        
        $terrain->setPotentiel($request->get('potentiel'));

        $terrain->setAdresse($request->get('adresse'));
        $em->persist($terrain);
        $em->flush();
        $jsonContent =$normalizer->normalize($terrain, 'json' ,['groups'=>'post:read']);
        return new Response("information updated successfully". json_encode($jsonContent));

    }

    #[Route('/api/deleteTerrainApi/{id}', name: 'deleteTerrainApi')]
    public function deleteTerrainApi(Request $request,NormalizerInterface $normalizer,$id): Response
    {

        $em = $this->getDoctrine()->getManager(); // ENTITY MANAGER ELY FIH FONCTIONS PREDIFINES

        $terrain = $this->getDoctrine()->getManager()->getRepository(Terrain::class)->find($id); // ENTITY MANAGER ELY FIH FONCTIONS PREDIFINES

            $em->remove($terrain);
            $em->flush();
            $jsonContent =$normalizer->normalize($terrain, 'json' ,['groups'=>'post:read']);
            return new Response("information deleted successfully".json_encode($jsonContent));
    }
}







