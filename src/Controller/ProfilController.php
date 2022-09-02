<?php

namespace App\Controller;

use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

 /**
     * @Route("/profil")
     */
class ProfilController extends AbstractController
{
    /**
     * @Route("/", name="app_profil")
     */
    public function index(): Response
    {
        return $this->render('profil/index.html.twig');
    }
    /**
     * @Route("/liste", name="app_liste")
     */
    public function Liste(ProduitRepository $produitRepository): Response
    {
        return $this->render('liste/index.html.twig', [
            "listes" => $produitRepository->findAll(),
        ]);
    }
}
