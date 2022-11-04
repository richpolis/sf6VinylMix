<?php

namespace App\Controller;

use App\Service\MixRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function Symfony\Component\String\u;

class VinylOldController extends AbstractController
{
    public function __construct(
        private bool $isDebug,
        private MixRepository $mixRepository
    )
    {}

    #[Route('/old', name: 'app_old_homepage')]
    public function homepage(): Response
    {
        $tracks = [
            ['song' => 'Gangsta\'s Paradise', 'artist' => 'Coolio'],
            ['song' => 'Waterfalls', 'artist' => 'TLC'],
            ['song' => 'Creep', 'artist' => 'Radiohead'],
            ['song' => 'Kiss from a Rose', 'artist' => 'Seal'],
            ['song' => 'On Bended Knee', 'artist' => 'Boyz II Men'],
            ['song' => 'Fantasy', 'artist' => 'Mariah Carey'],
        ];

        return $this->render('vinyl/homepage.html.twig', [
            'title' => 'PB & Jams',
            'tracks' => $tracks,
        ]);
    }

    #[Route('/old/browse/{slug}', name: 'app_old_browse')]
    public function browse(string $slug = null): Response
    {
        dump($this->isDebug);
        $genre = $slug ? u(str_replace('-', ' ', $slug))->title(true) : null;

        $mixes = $this->mixRepository->findAll();

        return $this->render('vinyl/old/browse.html.twig', [
            'genre' => $genre,
            'mixes' => $mixes,
        ]);
    }
}
