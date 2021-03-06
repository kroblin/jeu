<?php

namespace App\Controller;


use App\Entity\Joueur;
use App\Entity\Partie;
use App\Repository\CarteRepository;
use App\Repository\JoueurRepository;
use App\Repository\PartieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class PartieController extends AbstractController
{
    /**
     * @Route("/partie", name="partie")
     */
    public function index(JoueurRepository $joueurRepository, PartieRepository $partieRepository){
        $joueurs = $joueurRepository->findAll();
        $parties = $this->getUser()->getToutesMesParties();


        return $this->render('partie/index.html.twig', [
            'joueurs' => $joueurs,
            'parties' => $parties
        ]);
    }

    /**
     * @Route("/nouvelle-partie", name="nouvelle_partie")
     */

    public function nouvellePartie(
        Request $request,
        JoueurRepository $joueurRepository,
        CarteRepository $carteRepository
    ){
        $joueur1 = $this->getUser();
        $joueur2 = $joueurRepository->find($request->request->get('adversaire'));


        $cartes = $carteRepository->findAll();

        $partie = new Partie();
        $partie->setJoueur1($joueur1);
        $partie->setJoueur2($joueur2);

        $tabCartes = [];
        foreach ($cartes as $cartes) {
            $tabCartes[] = $cartes->getId();
        }
        shuffle($tabCartes);

        $mainJ1 = [];
        $mainJ2 = [];
        for ($i = 0; $i < 6; $i++){
            $mainJ1[] = array_pop($tabCartes);
            $mainJ2[] = array_pop($tabCartes);
        }

        $pioche = $tabCartes;

        for ($i = 1; $i <= 9; $i++) {

        }

        $partie->setMainJ1($mainJ1);
        $partie->setMainJ2($mainJ2);
        $partie->setPioche($pioche);
        $partie->setTypeVictoire('');
        $partie->setDateDebut(new \DateTime('now'));
        $partie->setTour(1);

        $terrainJ1 = [];
        $terrainJ2 = [];
        for($i = 1; $i <= 9; $i++) {
            $terrainJ1[$i][1] = 0;
            $terrainJ1[$i][2] = 0;
            $terrainJ1[$i][3] = 0;
            $terrainJ2[$i][1] = 0;
            $terrainJ2[$i][2] = 0;
            $terrainJ2[$i][3] = 0;
        }
        $partie->setTerrainJ1($terrainJ1);
        $partie->setTerrainJ2($terrainJ2);

        $em = $this->getDoctrine()->getManager();
        $em->persist($partie);
        $em->flush();

        return $this->redirectToRoute('afficher_partie', ['idPartie' => $partie->getId()]);
    }
    /**
     * @param Request $request
     * @Route("/depose_carte/{idPartie}", name="depose_carte")
     */
    public function deposeCarte(Request $request, Partie $idPartie, PartieRepository $partieRepository, CarteRepository $carteRepository) {

        $carte = $request->request->get('carte');
        $colonne = $request->request->get('colonne');
        $ligne = $request->request->get('ligne');


        //Gestion du jeu en fonction du tour
        $tour = $idPartie->getTour();

        if ($tour == 1){
            //ON défini le terrain du joueur 1
            $terrainJ1 = $idPartie->getTerrainJ1();
            $terrainJ1[$colonne][$ligne] = $carte;
            $idPartie->setTerrainJ1($terrainJ1);

            //Retirer la carte de la main et piocher
            $mainj1 = $idPartie->getMainJ1();
            $pioche = $idPartie->getPioche();

            //On retire l'id de la carte déposée de la main
            $array = array($carte);
            $mainj1 =  array_diff($mainj1, $array);

            //On pioche si il y a des cartes
            if (!empty($pioche)){
                $mainj1[] = array_pop($pioche);
            }

            //Fin de tour
            $tour = 2;
            $idPartie->setTour($tour);
            $idPartie->setMainJ1($mainj1);
        } elseif ($tour == 2){
            //ON défini le terrain du joueur
            $terrainJ2 = $idPartie->getTerrainJ2();
            $terrainJ2[$colonne][$ligne] = $carte;
            $idPartie->setTerrainJ2($terrainJ2);

            //Retirer la carte de la main et piocher
            $mainj2 = $idPartie->getMainJ2();
            $pioche = $idPartie->getPioche();

            //On retire l'id de la carte déposée de la main
            $array = array($carte);
            $mainj2 =  array_diff($mainj2, $array);

            //On pioche si il y a des cartes
            if (!empty($pioche)){
                $mainj2[] = array_pop($pioche);
            }

            //Fin de tour
            $tour = 1;
            $idPartie->setTour($tour);
            $idPartie->setMainJ2($mainj2);
        }
        $idPartie->setPioche($pioche);


        $cartes = $carteRepository->findAll();

//        dump($cartes);


        //Condition de capture des tuiles
        sort($terrainJ1[$colonne]);

//        dump($terrainJ1[$colonne]);


        for ($l = 0; $l <= 2; $l++) {
            //On évite la surcharge de calcul en vérifiant si il y a bien 3 cartes
            if (!empty($terrainJ1[$colonne][0])) {
//                dump($cartes[$terrainJ1[$colonne][$l]]->getCouleur());
                $couleur[] = $cartes[$terrainJ1[$colonne][$l]]->getCouleur();
                $puissance[] = $cartes[$terrainJ1[$colonne][$l]]->getPuissance();

            } else {
                dump('la colonne n\'est pas complète');
            }

        }

        dump($couleur);
        dump($puissance);

        if ($couleur[0] = $couleur[1] = $couleur[2]) {

        }


//
//        for ($i=1; $i <= 9; $i++) {
//            sort($terrainJ1[$i]);
//
//            var_dump($cartes[$terrainJ1[$i][2]]);
//            dump($cartes[$terrainJ1[$i][2]]->getCouleur());
//
//
//        }













        $em = $this->getDoctrine()->getManager();
        $em->persist($idPartie);
//        $em->flush();

    }




    /**
     * @Route("/partie/{idPartie}", name="afficher_partie")
     */


    public function affichePartie(Partie $idPartie){

        return $this->render('partie/afficher_partie.html.twig', [
            'partie' => $idPartie
        ]);
    }

    public function afficherPlateau(CarteRepository $carteRepository, Partie $idPartie, JoueurRepository $joueurRepository) {
        $cartes = $carteRepository->findAll();
        $tCartes = [];
        foreach ($cartes as $carte)
        {
            $tCartes[$carte->getId()] = $carte;
        }

        $joueurs = $joueurRepository->findAll();
        $user = $this->getUser();


        return $this->render('partie/plateau.html.twig', [
            'partie' => $idPartie,
            'cartes' => $tCartes,
            'joueurs' => $joueurs,
            'user' => $user
        ]);
    }



}
