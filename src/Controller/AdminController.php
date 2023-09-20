<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\Note;
use App\Form\GameType;
use App\Repository\GameRepository;
use App\Repository\NoteRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/admin')]
class AdminController extends AbstractController
{
    #[Route('/dashboard', name: 'app_admin')]
    public function index(GameRepository $gameRepository): Response
    {
        return $this->render('game/index.html.twig', [
            'games' => $gameRepository->findAll(),
        ]);
    }

    //methode pour afficher le detail dun jeu
    #[Route('/detail/{id}', name: 'app_game_show')]
    public function gameDetailDashboard(GameRepository $gameRepository, int $id)
    {

        $game = $gameRepository->getGameWithInfo($id);
        $consoles = $gameRepository->getConsolesByGame($id);

        return $this->render('game/show.html.twig', [
            'game' => $game,
            'consoles' => $consoles

        ]);
    }

    //Méthode pour supprimer un jeu
    #[Route('/delete/{id}', name: 'app_game_delete')]
    public function delete(Request $request, Game $game, GameRepository $gameRepository)
    {
        if ($this->isCsrfTokenValid('delete' . $game->getId(), $request->request->get('_token'))) {
            $gameRepository->delete($game, true);
        }
        return $this->redirectToRoute('app_admin', [], Response::HTTP_SEE_OTHER);
    }

    //méthode pour ajouter un jeu 
    #[Route('/new', name: 'app_game_new', methods: ['GET', 'POST'])]
    public function new(Request $request, GameRepository $gameRepository, NoteRepository $noteRepository)
    {
        $game = new Game();
        $form = $this->createForm(GameType::class, $game);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) 
        {
            // gestion de l'image uploadée
            $imageFile = $form->get('image')->getData();
            if($imageFile)
            {
                //si une image est uploadée, on récupere son nom d'origine
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                //on génere un nom unique pour l'image pour evité decraser des images ayant le meme nom
                $newFilename = $originalFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();
                Try{
                    //on déplace l'image uploadée dans le dossier public/images
                    $imageFile->move(
                        //games_images_directory est configuré dans config/services.yaml
                        $this->getParameter('game_images_directory'),
                        $newFilename
                    );
                }catch(FileException $e)
                {
                    $this->addFlash('danger', 'Une erreur est survenue lors de l\'upload de l\'images');
                }

                // on donne le nouveau nom pour la bdd
                $game->setImagePath($newFilename);
            }
            // on récupere les notes pour le jeu
            $userNote = $form->get('note')->get('userNote')->getData();
            $mediaNote = $form->get('note')->get('mediaNote')->getData();
            //on crée une nouvelle ligne dans note (bdd) et on ajoute les notes
            $note = new Note();
            $note->setUserNote($userNote);
            $note->setMediaNote($mediaNote);
            //on enregistre la note dans la bdd 
            $noteRepository->save($note, true);
            // on récupere l'id de la note pour la donnée au jeu jeu
            $game->setNote($noteRepository->find($note->getId()));
            $gameRepository->save($game,true);

            return $this->redirectToRoute('app_admin',[], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('game/new.html.twig', [
            'game' => $game,
            'form' => $form,
        ]);
    }

    //Méthode pour modifier un jeu
    #[Route('/edit/{id}', name: 'app_game_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request,Game $game, GameRepository $gameRepository)
    {
        $form = $this->createForm(GameType::class, $game);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {

            // gestion de l'image uploadée
            $imageFile = $form->get('image')->getData();
            if($imageFile)
            {
                //si une image est uploadée, on récupere son nom d'origine
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                //on génere un nom unique pour l'image pour evité decraser des images ayant le meme nom
                $newFilename = $originalFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();
                Try{
                    //on déplace l'image uploadée dans le dossier public/images
                    $imageFile->move(
                        //games_images_directory est configuré dans config/services.yaml
                        $this->getParameter('game_images_directory'),
                        $newFilename
                    );
                }catch(FileException $e)
                {
                    $this->addFlash('danger', 'Une erreur est survenue lors de l\'upload de l\'images');
                }

                // on donne le nouveau nom pour la bdd
                $game->setImagePath($newFilename);
            }
            
            $gameRepository->save($game, true);
            return $this->redirectToRoute('app_admin', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('game/edit.html.twig', [
            'game' => $game,
            'form' => $form
        ]);
    }
}
