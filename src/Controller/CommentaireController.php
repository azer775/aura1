<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Form\CommentaireType;
use App\Repository\CommentaireRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;


#[Route('/commentaire')]
class CommentaireController extends AbstractController
{

    #[Route('/listeeCmntsJson', name: 'app_cmnt_listeCmnts', methods: ['GET'])]
    public function getComments(CommentaireRepository $cmntRepository, SerializerInterface $serializer): JsonResponse
{
    $cmnts = $cmntRepository->findAll();
    return $this->json($cmnts, 200, [], ['groups' => 'cmnts']);
    
}
#[Route("/deleteComment/{id}")]
public function deletePost(Request $req ,$id,NormalizerInterface $normalizer,CommentaireRepository $commentaireRepository)
{
    $comment =$commentaireRepository->find($id);
    $commentaireRepository->remove($comment,true);
    $jsonContent =$normalizer->normalize($comment, 'json',['groups'=>'cmnts'] );
    return new Response("Comment deleted successsfully" . json_encode($jsonContent));
}
#[Route('/addCommentJson', name: 'app_cmnt_addJSON', methods: ['GET','POST'])]
public function addCmnts(Request $request,EntityManagerInterface $em): Response
{
   
    $Cmntr = new Commentaire();
    $text = $request->query->get("text");
    $date = new \DateTime('now');

    $Cmntr->setText($text);

    $Cmntr->setDate($date);

    $em->persist($Cmntr);
    $em->flush();

    return $this->json($Cmntr,200,[],['groups'=>'cmnts']);


}
    #[Route('/', name: 'app_commentaire_index', methods: ['GET'])]
    public function index(CommentaireRepository $commentaireRepository): Response
    {
        return $this->render('commentaire/index.html.twig', [
            'commentaires' => $commentaireRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_commentaire_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CommentaireRepository $commentaireRepository): Response
    {
        $commentaire = new Commentaire();
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commentaireRepository->save($commentaire, true);

            return $this->redirectToRoute('app_commentaire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('commentaire/new.html.twig', [
            'commentaire' => $commentaire,
            'form' => $form,
        ]);
    }

    #[Route('cmnts/{id}', name: 'app_commentaire_show', methods: ['GET'])]
    public function show(Commentaire $commentaire): Response
    {
        return $this->render('commentaire/show.html.twig', [
            'commentaire' => $commentaire,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_commentaire_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Commentaire $commentaire, CommentaireRepository $commentaireRepository): Response
    {
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commentaireRepository->save($commentaire, true);

            return $this->redirectToRoute('app_commentaire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('commentaire/edit.html.twig', [
            'commentaire' => $commentaire,
            'form' => $form,
        ]);
    }
    

    #[Route("/{id}/updateComment", name:'comment_updat',methods: ['GET', 'POST'])]
    public function updateComment(Commentaire $commentaire,Request $request,CommentaireRepository $commentaireRepository)
    {
        $session = $request->getSession();
        $membre = $session->get('user');

        $formComm = $this->createForm(CommentaireType::class, $commentaire);
        $formComm->handleRequest($request);
       
        $httpClient = HttpClient::create();
        
        if ($formComm->isSubmitted() && $formComm->isValid() ) {
            // $commentaireRepository->save($commentaire, true);
            //filter for bad words:
            $content = $commentaire->getText();
            $response = $httpClient->request('GET', 'https://neutrinoapi.net/bad-word-filter', [
                'query' => [
                    'content' => $content
                ],
                'headers' => [
                    'User-ID' => 'dhiadhia',
                    'API-Key' => 'LFJj4XqhKYZsY8yzSm7wg08kTOeQF7rtA4MZYQZGVSVJa9ZG',
                ]
            ]);
            if ($response->getStatusCode() === 200) {
                $result = $response->toArray();
                if ($result['is-bad']) {
                    // Handle bad word found
                    $this->addFlash('danger', 'Your comment contains inappropriate language and cannot be updated.');
                    return $this->redirectToRoute('app_post_singlepage', ['id' => $commentaire->getPost()->getId(),'redirected' => true], Response::HTTP_SEE_OTHER);
                } else {
                    // Save comment
                    $this->addFlash('success', 'Your comment has been updated successfully.');
        
                    $commentaireRepository->save($commentaire, true);
                    return $this->redirectToRoute('app_post_singlepage', ['id' => $commentaire->getPost()->getId(),'redirected' => true], Response::HTTP_SEE_OTHER);
                }
            } else {
                // Handle API error
                
                return new Response("Error processing request", Response::HTTP_INTERNAL_SERVER_ERROR);
            } 
           
        }
        return $this->renderForm('post/editFrontComm.html.twig', [
            'formComm' => $formComm,
            'user' => $membre,
        ]);
    }
   #[Route("/comment/{id}/delete", name:'comment_delete')]
    public function deleteComment(Commentaire $comment)
{
    
    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->remove($comment);
    $entityManager->flush();
    $this->addFlash('success', 'Your comment was successfully deleted.');
    return $this->redirectToRoute('app_post_singlepage', ['id' => $comment->getPost()->getId(),'redirected' => true]);
}

    #[Route('/{id}/delete', name: 'app_commentaire_delete', methods: ['POST'])]
    public function delete(Request $request, Commentaire $commentaire, CommentaireRepository $commentaireRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commentaire->getId(), $request->request->get('_token'))) {
            $commentaireRepository->remove($commentaire, true);
        }

        return $this->redirectToRoute('app_commentaire_index', [], Response::HTTP_SEE_OTHER);
    }
    
  /*  #[Route('/addCommentJson', name: 'app_cmnt_addJSON', methods: ['GET','POST'])]
    public function addCmnts(Request $request, SerializerInterface $serializer, EntityManagerInterface $em): Response
    {
        $content = $request->getContent();
        $data = $serializer->deserialize($content,Commentaire::class,'json');
        $em->persist($data);
        $em->flush();
        return new Response("Comment added successfully");
    
    }
*/

}
