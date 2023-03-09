<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Commentaire;
use App\Form\SearchFormType;
use App\Form\PostType;
use App\Entity\Rating;
use App\Form\RatingType;
use App\Form\CommentaireType;
use App\Repository\PostRepository;
use App\Repository\CommentaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpClient\HttpClient;

use Knp\Component\Pager\PaginatorInterface;


#[Route('/post')]
class PostController extends AbstractController
{

    
    #[Route('/', name: 'app_post_index', methods: ['GET'])]
    public function index(PostRepository $postRepository,Request $request,PaginatorInterface $p): Response
    
    {
        $session = $request->getSession();
        $membre = $session->get('user');
    
        $popularPosts = $postRepository->findPopularPosts();
        $popularPosts=$p->paginate($popularPosts,
        $request->query->getInt('page',1),4);
        $recents = $postRepository
        ->createQueryBuilder('p')
        ->orderBy('p.date_Creation', 'DESC')
        ->setMaxResults(3)
        ->getQuery()
        ->getResult();

    
    
        return $this->render('post/index.html.twig', [
            'user' => $membre,
            'posts' => $popularPosts,
            'recents' => $recents
        
        ]);
        
    }

    #[Route('/new', name: 'app_post_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PostRepository $postRepository): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $postRepository->save($post, true);

            return $this->redirectToRoute('app_post_affiche', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('post/new.html.twig', [
            'post' => $post,
            'form' => $form,
            
        ]);
    }

    #[Route('/search', name: 'app_post_search', methods: ['GET'])]
   
    public function search(PostRepository $postRepository, Request $request): JsonResponse
    {
        $query = $request->query->get('q');
        $date = $request->query->get('date');
        dump($query, $date);
    
        $dateObj = \DateTime::createFromFormat('Y-m-d', $date);
        if ($dateObj === false) {
            // $date is not a valid date format, so set it to null
            $date = null;
        }
    
        $results = [];
        if ($query !== null || $date !== null) {
            $results = $postRepository->createQueryBuilderForSearch($query, $date)->getQuery()->getResult();
        }
    
        $response = [];
        foreach ($results as $result) {
            $response[] = [
                'url' => $this->generateUrl('app_post_singlepage', ['id' => $result->getId()]),
                'nom' => $result->getNom(),
            ];
        }
    
        return new JsonResponse($response);
    }
     
    
    #[Route('posts/{id}', name: 'app_post_singlepage', methods: ['GET','POST'])]
    public function singlepage(Post $post,PostRepository $postRepository,Request $request, CommentaireRepository $commentaireRepository): Response
    {

        $session= $request->getSession();
        $membre=$session->get('user');
        $commentaire = new Commentaire();
        $commentaire -> setPost($post);
        $commentaire->setDate(new \DateTime());
        //$commentaire->setMembre($membre);
  
        $formComm = $this->createForm(CommentaireType::class, $commentaire);
        $formComm->handleRequest($request);
       /* $toxicity = $perspectiveApi->analyzeComment($commentaire->getText())['attributeScores']['TOXICITY']['summaryScore']['value'];

        if ($toxicity > 0.8) {
            return new Response('Commentaire inapproprié détecté.');
        }*/
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
                    'User-ID' => 'ysf',
                    'API-Key' => 'y1sHojwhnPNtbeRwYt90l6nUiluLvAP6WUfH4cHDjoeMCRj2',
                   
                ]
            ]);
            if ($response->getStatusCode() === 200) {
                $result = $response->toArray();
                if ($result['is-bad']) {
                    // Handle bad word found
                    $this->addFlash('danger', 'Your comment contains inappropriate language and cannot be posted.');
                    return $this->redirectToRoute('app_post_singlepage', ['id' => $post->getId(),'redirected' => true], Response::HTTP_SEE_OTHER);
                } else {
                    // Save comment
                    $commentaireRepository->save($commentaire, true);
                    $this->addFlash('success', 'Your comment has been posted successfully.');
                   
                    return $this->redirectToRoute('app_post_singlepage', ['id' => $post->getId(),'redirected' => true], Response::HTTP_SEE_OTHER);
                }
            } else {
                // Handle API error
                
                return new Response("Error processing request", Response::HTTP_INTERNAL_SERVER_ERROR);
            } 
           
        }
        
    
        $ratingForm = $this->createForm(RatingType::class);
        $ratingForm->handleRequest($request);
    
        if ($ratingForm->isSubmitted() && $ratingForm->isValid()) {
            $data = $ratingForm->getData();
            $rating = new Rating();
            $rating->setPost($post);
            $rating->setMembre($this->getUser());
            $rating->setRate($data->getRate());
            $rating->setDateCreation(new \DateTime());
    
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($rating);
            $entityManager->flush();
            return $this->redirectToRoute('app_post_singlepage', ['id' => $post->getId()], Response::HTTP_SEE_OTHER);
        }
        $ratings = $post->getRatings();
        $totalLikes = $totalDislikes = 0;

        foreach ($ratings as $rating) {
            if ($rating->getRate() == 1) {
                $totalLikes++;
            } elseif ($rating->getRate() == -1) {
                $totalDislikes++;
            }
        }

        $recents = $postRepository
        ->createQueryBuilder('p')
        ->orderBy('p.date_Creation', 'DESC')
        ->setMaxResults(3)
        ->getQuery()
        ->getResult();

        $mayLikes = $postRepository
        ->createQueryBuilder('p')
        ->where('p.theme = :theme')
        ->setParameter('theme', $post->getTheme()) // Remplacez $post par l'objet Post en question
        ->andWhere('p.id != :postId')
        ->setParameter('postId', $post->getId()) // Pour éviter de sélectionner le post en question lui-même
        ->orderBy('p.theme', 'DESC')
        ->setMaxResults(3)
        ->getQuery()
        ->getResult();

        $url = $this->generateUrl('app_post_singlepage', ['id' => $post->getId()], UrlGeneratorInterface::ABSOLUTE_URL);
   


        return $this->render('post/singlepage.html.twig', [
            'post' => $post,
            'posts' => $postRepository->findAll(),
            'commentaire' => $commentaire,
            'formComm' => $formComm->createView(),
            'user' => $membre,
            'ratingForm' => $ratingForm->createView(),
            'likes' => $totalLikes, 
            'dislikes' => $totalDislikes,
            'share_url' => $url,
            'recents' => $recents,
            'mayLikes' => $mayLikes
        ]);
       
    }

    #[Route('poste/{id}', name: 'app_post_show', methods: ['GET'])]
    public function show(Post $post,PostRepository $postRepository): Response
    {
        return $this->render('post/show.html.twig', [
            'post' => $post,
            'posts' => $postRepository->findAll(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_post_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Post $post, PostRepository $postRepository): Response
    {
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $postRepository->save($post, true);

            return $this->redirectToRoute('app_post_affiche', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('post/edit.html.twig', [
            'post' => $post,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_post_delete', methods: ['POST'])]
    public function delete(Request $request, Post $post, PostRepository $postRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$post->getId(), $request->request->get('_token'))) {
            $postRepository->remove($post, true);
        }

        return $this->redirectToRoute('
        app_post_affiche', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/postss', name: 'app_post_affiche', methods: ['GET'])]
    public function aff(PostRepository $PostRepository): Response
    {
        return $this->render('post/afficher.html.twig', [
            'posts' => $PostRepository->findAll(),
        ]);
    }
    #[Route('/jsoooooon', name: 'app_post_liste',methods:['GET','POST'])]
    public function getPosts(PostRepository $postRepository, SerializerInterface $serializer): JsonResponse
{
    $posts = $postRepository->findAll();
    $json = $serializer->serialize($posts,'json',['groups' => 'posts']);
    
    return $this->json($json,200,[],['groups'=>'posts']);
}
   /* #[Route('/addJson', name: 'app_post_addJSON', methods: ['GET','POST'])]
    public function addPosts(Request $request, SerializerInterface $serializer, EntityManagerInterface $em): Response
    {
        $content = $request->getContent();
        $data = $serializer->deserialize($content,Post::class,'json');
        $em->persist($data);
        $em->flush();
        return new Response("Post added successfully");
    
    }*/
    #[Route('/addJson', name: 'app_post_addJSON', methods: ['GET','POST'])]
    public function addPosts(Request $request,EntityManagerInterface $em): Response
    {
        $Post = new Post();
        $theme = $request->query->get("theme");
        $contenu=$request->query->get("contenu");
        $nom=$request->query->get("nom");
        $image=$request->query->get("image");
        $date = new \DateTime('now');

        $Post->setImage($image);
        $Post->setTheme($theme);
        $Post->setNom($nom);
        $Post->setContenu($contenu);
        $Post->setDateCreation($date);


        $em->persist($Post);
        $em->flush();

        return $this->json($Post,200,[],['groups'=>'posts']);
    }

    #[Route("/deletePost/{id}")]
        public function deleteEvent(Request $req ,$id,NormalizerInterface $normalizer,PostRepository $postRepository)
        {
            $post =$postRepository->find($id);
            $postRepository->remove($post,true);
            $jsonContent =$normalizer->normalize($post, 'json',['groups'=>'posts'] );
            return new Response("Post deleted successsfully" . json_encode($jsonContent));
        }


        #[Route("/updatePost/{id}")]
        public function updateEvent(Request $req ,$id,NormalizerInterface $normalizer,PostRepository $postRepository)
        {
            $post = $postRepository->find($id);
            //$event->setImage($req->get('image'));
            $post->setNom($req->get('nom'));
            $post->setTheme($req->get('theme'));
            $post->setContenu($req->get('contenu'));
            $post->setImage($req->get('image'));
            $date = new \DateTime('now');
            $post->setDateCreation($date);
        
            $postRepository->save($post,true);
            $jsonContent =$normalizer->normalize($post, 'json', ['groups' => 'posts']);
            return new Response("Post updated successsfully" . json_encode($jsonContent));
        }


   /* #[Route('/UpdateJson', name: 'app_post_addJSON', methods: ['GET','POST'])]
  
    public function updatePosts(Request $request, SerializerInterface $serializer, EntityManagerInterface $em,Post $p): Response
    {
    
        $theme = $request->query->get("theme");
        $contenu=$request->query->get("contenu");
        $nom=$request->query->get("nom");
        $image=$request->query->get("image");
       // $nbr_Vue=$request->query->get("nbr_Vue");
       $date = $request->query->get("date");
       $date = new \DateTime('now');
       
       $p->setDateCreation($date);

        $p->setImage($image);
        $p->setTheme($theme);
        $p->setNom($nom);
        $p->setContenu($contenu);
        $p->setDateCreation($date);

        $em->persist($p);
        $em->flush();

        return $this->json($p,200,[],['groups'=>'posts']);
    }
  } */
  
  /*  #[Route('/newComment', name: 'app_commentaire_new', methods: ['POST'])]
    public function newComment(Request $request, CommentaireRepository $commentaireRepository): Response
    {
        $commentaire = new Commentaire();
        $commentaire->setDate(new \DateTime());
      //  $commentaire->setPost($post);
        $formComm = $this->createForm(CommentaireType::class, $commentaire);
        $formComm->handleRequest($request);
        if ($formComm->isSubmitted() && $formComm->isValid()) {
            $commentaireRepository->save($commentaire, true);
            return $this->redirectToRoute('app_post_singlepage', [], Response::HTTP_SEE_OTHER);
}

        return $this->render('post/singlepage.html.twig', [
            'commentaire' => $commentaire,
            'formComm' => $formComm,
        ])

}*/

}