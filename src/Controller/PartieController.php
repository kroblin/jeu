<?php

namespace App\Controller;


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

        $partie->setMainJ1($mainJ1);
        $partie->setMainJ2($mainJ2);
        $partie->setPioche($pioche);
        $partie->setTypeVictoire('');
        $partie->setDateDebut(new \DateTime('now'));
        $partie->setTour(1);

        $em = $this->getDoctrine()->getManager();
        $em->persist($partie);
        $em->flush();

        return $this->redirectToRoute('afficher_partie', ['idPartie' => $partie->getId()]);
    }
    /**
     * @param Request $request
     * @Route("/depose_carte/{idPartie}", name="depose_carte")
     */
    public function deposeCarte(Request $request, Partie $idPartie, PartieRepository $partieRepository) {
//        dump($request->request->get('carte'));
//        dump($request->request->get('colonne'));
//        dump($request->request->get('ligne'));
        $partie = $partieRepository->find($idPartie);

        $terrainJ1 =  $partie->getTerrainJ1();

        $cartes[] = [
            $request->request->get('carte'),
            $request->request->get('colonne'),
            $request->request->get('ligne')
        ];

        $terrainJ1[] = array_pop($cartes);
        var_dump($cartes);




        $partie->setTerrainJ1($terrainJ1);





        $em = $this->getDoctrine()->getManager();
        $em->persist($partie);
        $em->flush();

    }



    /**
     * @Route("/partie/{idPartie}", name="afficher_partie")
     */


    public function affichePartie(
        CarteRepository $carteRepository,
        Partie $idPartie,
        PartieRepository $partieRepository
    ){
        $cartes = $carteRepository->findAll();
        $tCartes = [];
        foreach ($cartes as $carte){
            $tCartes[$carte->getId()] = $carte;
        }

        $partie = $partieRepository->find($idPartie);

        $tour = $idPartie->getTour();
        if ($tour == 1){
            $partie->setTour(2);
        } else {
            $partie->setTour(1);
        }

        $terrainJ1 =  $partie->getTerrainJ1();
        //var_dump($terrainJ1);
        //var_dump($terrainJ1[0][1]);
        //var_dump($tCartes);



        return $this->render('partie/afficher_partie.html.twig', [
            'partie' => $idPartie,
            'cartes' => $tCartes,
            'terrainJ1' => $terrainJ1
        ]);
    }
}
