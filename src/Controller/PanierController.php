<?php

namespace App\Controller;

use App\Entity\Detail;
use App\Entity\Produit;
use App\Entity\Commande;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\DateTime;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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

     
    /**
     * @Route("/vider", name="app_panier_vider")
     */

     public function vider(Session $session)
     {
        $session->remove("panier");
        return $this->redirectToRoute("app_panier");
     }

        /**
     * @Route("/supprimer-produit-{id}", name="app_panier_supprimer", requirements={"id"="\d+"})
     */

    public function supprimer(Produit $produit, Session $session)
    {
       $panier = $session->get("panier", [] );
       foreach($panier as $indice => $ligne){
        if($produit->getId() == $ligne["produit"]->getId() ) {
            unset($panier[$indice]);
            break;
        }
    }
        $session->set("panier", $panier);
        return $this->redirectToRoute("app_panier");
    }

        /**
     * @Route("/valider", name="app_panier_valider")
     * @IsGranted("ROLE_CLIENT")
     */

    public function valider(Session $session, ProduitRepository $produitRepository, EntityManagerInterface $em)
    {
       $panier = $session->get("panier", []);
       if($panier){
           $cmd = new Commande;
           $cmd->setDateEnregistrement(new \DateTime());
           $cmd->setEtat("en attente");
           $cmd->setClient($this->getUser());
           $montant = 0;
           foreach($panier as $ligne){
               $produit = $produitRepository->find($ligne["produit"]->getId() );
               $montant += $produit->getPrix() * $ligne["quantite"];

               $detail = new Detail;
               $detail->setPrix($produit->getPrix() );
               $detail->setQuantite($ligne["quantite"]);
               $detail->setProduit( $produit );
               $detail->setCommande($cmd);
               $em->persist($detail);

               $produit->setStock($produit->getStock() - $ligne["quantite"] );
           }
           $cmd->setMontant($montant);
           $em->persist($cmd);
           $em->flush();
           $session->remove("panier");
           $this->addFlash("success", "Votre commande a bien été enregistrée");
           return $this->redirectToRoute("app_panier");
       }
       $this->addFlash("danger", "Le panier est vide. Vous ne pouvez pas valider la commande.");
       return $this->redirectToRoute("app_panier");
    }   

}
