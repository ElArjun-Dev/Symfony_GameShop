<?php 

namespace App\Controller;

use App\Repository\GameRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    //ici on déclare sa route
    #[Route('/', name: 'index')]
   public function index(GameRepository $gameRepository)
   {

    //on peut déclarer des variables
    $title = 'Tous les jeux';
    //variable qui récupere la liste des jeux 
    $games = $gameRepository->findAll();

       return $this->render('home/index.html.twig',[
        'title' => $title,
        'games' => $games
       ]);
   }


   #[Route('/detail/{id}', name: 'detail')]
   public function gameById(GameRepository $gameRepository, int $id)
   {
    //TODO : Appel du repository pour récupérer le jeu avec toutes les info par son id
    $game = $gameRepository->getGameWithInfo($id);

    $consoles = $gameRepository->getConsolesByGame($id);


    return $this->render('home/detail.html.twig',[
        'game' => $game,
        'consoles' => $consoles
    ]);
   }

   #[Route('/console/{id}', name: 'consoleID')]
   public function gameByConsole(GameRepository $gameRepository, int $id)
   {

       
       $games = $gameRepository->getGamesByConsole($id);
       $title = 'Tous les jeux: ' . $games[0]['label'];

    return $this->render('home/index.html.twig',[
        'title' => $title,
        'games' => $games
    ]);
   }
}