<?php
namespace App\Controller;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StatsController extends AbstractController
{
    #[Route('/stats', name: 'app_stats')]
    public function index(PostRepository $postRepository): Response
    {
        $stats = $postRepository->getCommentsStats();

        $data = [];
        $data[] = ['Post', 'Number of Comments'];

        foreach ($stats as $postStats) {
            $data[] = [$postStats['nom'], $postStats['commentsCount']];
        }

        return $this->render('post/stats.html.twig', [
            'chartData' => json_encode($data),
        ]);
    }
}
