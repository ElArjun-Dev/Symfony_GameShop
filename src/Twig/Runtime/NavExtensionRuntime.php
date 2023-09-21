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

    public function ageItems()
    {
        return $this->gameRepository->getCountGameByAge();
    }

    public function filterItems()
    {

        return [
            ['label' => 'Prix', 'filter' => 'g.price ASC', 'icon' => 'fa-sharp fa-solid fa-arrow-up'],
            ['label' => 'Prix', 'filter' => 'g.price DESC', 'icon' => 'fa-sharp fa-solid fa-arrow-down'],
            ['label' => 'Date de sortie', 'filter' => 'g.releaseDate ASC', 'icon' => 'fa-sharp fa-solid fa-arrow-up'],
            ['label' => 'Date de sortie', 'filter' => 'g.releaseDate DESC', 'icon' => 'fa-sharp fa-solid fa-arrow-down'],
            ['label' => 'Note de presse', 'filter' => 'n.mediaNote ASC', 'icon' => 'fa-sharp fa-solid fa-arrow-up'],
            ['label' => 'Note de presse', 'filter' => 'n.mediaNote DESC', 'icon' => 'fa-sharp fa-solid fa-arrow-down'],
            ['label' => 'Note utilisateur', 'filter' => 'n.mediaNote ASC', 'icon' => 'fa-sharp fa-solid fa-arrow-up'],
            ['label' => 'Note utilisateur', 'filter' => 'n.mediaNote DESC', 'icon' => 'fa-sharp fa-solid fa-arrow-down'],
        ];
    }
}
