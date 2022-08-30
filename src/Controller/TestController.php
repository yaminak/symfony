<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    /**
     * @Route("/test", name="app_test")
     */
    public function index(): Response
    {
        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }
     /**
     * @Route("/test-base", name="app_test_base")
     */
    public function base()
    {
        return $this->render('base.html.twig', [
            "nombre" => 5,
            "nom" => "Cérien"
        ]);
    }
     /**
     * @Route("/test/calcul", name="app_test_calcul")
     */
    public function calcul()
    {
        $a = 9;
        $b = 23;
        return $this->render("test/calcul.html.twig", [
            "chiffre1" => $a,
            "chiffre2" => $b,
        ]);
    }
      /**
     * @Route("/test/calcul/{a}/{b?} ", name="app_test_calcul_dynamique", requirements= {"a"="\d+[.]?\d+", "b"="[0-9]+"})
     */
    public function CalculDynamique($a, $b)
    {
        $b = $b ?? 0;
        return $this->render("test/calcul.html.twig", [
            "chiffre1" => $a,
            "chiffre2" => $b,
        ]);
    }
}
