<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Mairie;

class AccueilController extends AbstractController
{

    /**
     * @Route("/", name="accueil")
     */
    public function index()
    {
        $mairies = $this->getDoctrine()->getRepository(Mairie::class)->findAll();

        return $this->render('accueil/index.html.twig', [
            'mairies' => $mairies,
        ]);
    }
}
