<?php

namespace App\Controller;

use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface as Session;

/**
     * @Route("/panier")
     */

class PanierController extends AbstractController
{
    /**
     * @Route("/", name="app_panier")
     */
    public function index(Session $session): Response
    {
    $panier =$session->get("panier", []);
        return $this->render('panier/index.html.twig', [
            'panier' => $panier,
        ]);
    }

    /**
     * @Route("/ajouter-produit-{id}", name="app_panier_ajouter", requirements={"id"="\d+"})
     */

     public function ajouter($id, ProduitRepository $pr, Session $session, Request $request)
     {
        $quantite = $request->query->get("qte", 1) ?: 1;
        $produit = $pr->find($id);
        $panier =$session->get("panier", []);

        $produitDejaDansPanier = false;
        foreach($panier as $indice => $ligne){
            if($produit->getId() == $ligne["produit"]->getId() ) {
                $panier[$indice]["quantite"] += $quantite;
                $produitDejaDansPanier = true;
                break;
            }
        }
        if(!$produitDejaDansPanier){
            $panier[] = [ "quantite" => $quantite, "produit" => $produit ];
        }
        
        $session->set("panier", $panier);
        return $this->redirectToRoute("app_home");
        // dd($produit); 
     }
}
