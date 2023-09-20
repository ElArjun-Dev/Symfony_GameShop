<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Age;
use App\Entity\Game;
use App\Entity\Note;
use App\Entity\User;
use App\Entity\Console;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    // propriété pour encoder le mot de pa
    private $encoder;
    //pripriété pour le fake
    private \Faker\Generator $faker;


    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
        $this->faker = Factory::create();
    }

    public function load(ObjectManager $manager): void
    {
        // Attention l'ordre est trés important
        //On récupere la méthode loadUser()
        $this->loadUser($manager);
        //On récupere la méthode loadConsole()
        $this->loadConsole($manager);
        //On récupere la méthode loadAge()
        $this->loadAge($manager);
        //On récupere la méthode loadNote()
        $this->loadNote($manager);
        //On récupere la méthode loadGame()
        $this->loadGame($manager);

        $manager->flush();
    }

    public function loadUser(ObjectManager $manager): void
    {
        //On instancie la class User
        $user = new User();
        //On set les valeurs pour créer un utilisateur
        $user->setEmail('admin@admin.com')
        ->setPassword($this->encoder->hashPassword($user, 'admin'))
        ->setRoles(['ROLE_ADMIN']);
        //On persist les données (role de préparation)
        $manager->persist($user);
    }

    //méthode pour créer nos consoles 
    public function loadConsole(ObjectManager $manager): void
    {
        //déclarer un tableau de consoles 
        $consoleArray = ['PS4', 'PS5', '360', 'XBOX Séries', 'ONE', 'SWITCH', 'PC'];
        //on boucle sur le tableau et on affilie les valeurs
        foreach ($consoleArray as $key => $cons) 
        {
            $console = new Console();
            $console->setLabel($cons);
            $manager->persist($console);
            //on définit une référence pour pouvoir faire nos relations avec console
            $this->addReference('console_' . $key + 1, $console);
        }
    }

    //méthode pour crée les ages
    public function loadAge(ObjectManager $manager): void
    {
        // Création d'un tableau d'age
        $ageArray = [
            ['key'=> 1, 'label' => '3' , 'imagePath' => 'pegi3.png'],
            ['key'=> 2, 'label' => '7' , 'imagePath' => 'pegi7.png'],
            ['key'=> 3, 'label' => '12' , 'imagePath' => 'pegi12.png'],
            ['key'=> 4, 'label' => '16' , 'imagePath' => 'pegi16.png'],
            ['key'=> 5, 'label' => '18' , 'imagePath' => 'pegi18.png'],
        ];

        //on boucle sur le tableau et on affilie les valeurs
        foreach ($ageArray as $key => $value)
        {
            $age = new Age();
            $age->setLabel($value['label']);
            $age->setImagePath($value['imagePath']);
            $manager->persist($age);
            //on définit une référence pour pouvoir faire nos relations avec age
            $this->addReference('age_' . $value['key'], $age);
        }
    }

    //Méthode pour créer les notes
    public function loadNote(ObjectManager $manager): void
    {
        // on crée une boucle for pour générér les ntoes entre 10 et 20
        for ($i = 1 ; $i <= 15; $i++)
        {
            $note = new Note();
            $note->setMediaNote(rand(10, 20));
            $note->setUserNote(rand(10, 20));
            $manager->persist($note);
            //on définit une référence pour pouvoir faire nos relations avec note
            $this->addReference('note_' . $i, $note);
        }

    }

    public function loadGame(ObjectManager $manager): void
    {
        $gameArray = [

            ["note" => 1, "age" => 1, "title" => "Animal Crossing : New Horizons", "imagePath" => "animal-crossing.jpg", "console" => [6]],
            ["note" => 2, "age" => 5, "title" => "Call of Duty : Modern Warfare 2", "imagePath" => "call-of-duty.jpg", "console" => [1, 2, 4, 5, 7]],
            ["note" => 3, "age" => 1, "title" => "Fall Guys : Ultimate Knockout", "imagePath" => "fall-guys.jpg", "console" => [1, 2, 4, 5, 7]],
            ["note" => 4, "age" => 1, "title" => "FIFA 23", "imagePath" => "fifa-23.jpg", "console" => [1, 2, 4, 5, 7]],
            ["note" => 5, "age" => 5, "title" => "Grand Theft Auto V", "imagePath" => "gta-v.jpg", "console" => [1, 2, 3, 4, 5, 7]],
            ["note" => 6, "age" => 2, "title" => "Human Fall Flat", "imagePath" => "Human-Fall-Flat.jpg", "console" => [1, 2, 4, 5, 7]],
            ["note" => 7, "age" => 1, "title" => "Mario Kart 8 Deluxe", "imagePath" => "mario-kart-8.jpg", "console" => [6]],
            ["note" => 8, "age" => 1, "title" => "Super Mario Odyssey", "imagePath" => "mario-odyssey.jpg", "console" => [6]],
            ["note" => 9, "age" => 2, "title" => "Minecraft", "imagePath" => "minecraft.jpg", "console" => [1, 2, 3, 4, 5, 7]],
            ["note" => 10, "age" => 2, "title" => "Légendes Pokémon: Arceus", "imagePath" => "pokemon.jpg", "console" => [6]],
            ["note" => 11, "age" => 4, "title" => "PlayerUnknown's Battlegrounds", "imagePath" => "PUBG-Battlegrounds.jpg", "console" => [1, 5, 7]],
            ["note" => 12, "age" => 5, "title" => "Red Dead Redemption II", "imagePath" => "red-dead-redemption.jpg", "console" => [1, 5, 7]],
            ["note" => 13, "age" => 5, "title" => "The Elder Scrolls V : Skyrim", "imagePath" => "The-Elder-Scrolls-Skyrim.jpg", "console" => [1, 2, 3, 4, 5, 7]],
            ["note" => 14, "age" => 3, "title" => "The Legend of Zelda : Breath of the Wild", "imagePath" => "zelda.jpg", "console" => [6]],
        ];

        //on crée la boucle 
        foreach ($gameArray as $value)
        {
            $game = new Game();
            $game->setTitle($value['title']);
            //fausse date de description
            $game->setDescription(implode(', ', $this->faker->words(10)));
            $game->setImagePath($value['imagePath']);
            //fausse date de sortie en timestamp
            $game->setReleaseDate($this->faker->dateTimeBetween('-10 years')->getTimestamp());
            $game->setPrice(rand(0,6000));
            //on appelle nos reference pour effectuer les relations
            $game->setNote($this->getReference('note_' . $value['note']));
            $game->setAge($this->getReference('age_' . $value['age']));
            //on doit boucler sur le tableau de consoles
            foreach ($value['console'] as $console)
            {
                $game->addConsole($this->getReference('console_' . $console));
            }
            $manager->persist($game);
        }
    }
}
