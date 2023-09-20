<?php

namespace App\Twig\Runtime;

use App\Repository\GameRepository;
use Twig\Extension\RuntimeExtensionInterface;

class NavExtensionRuntime implements RuntimeExtensionInterface
{
    //on déclare une variable en private pour stocjer notre instance 
    private $gameRepository;


    public function __construct(GameRepository $gameRepository)
    {
       $this->gameRepository = $gameRepository;
    }

    public function doSomething($value)
    {
        // ...
    }

    public function menuItems()
    {
        // on peut mintenant appeler la méthode getCountGameByConsole
        return $this->gameRepository->getCountGameByConsole();
    }
}
