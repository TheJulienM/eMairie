<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Entity\Mairie;
use App\Entity\Projets;
use App\Entity\Like;
use App\Form\ProjetsType;
use App\Form\MairieType;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class ProjetsController extends AbstractController
{
	 /**
     * @Route("/projets/{ville}/creation", name="mairie_projet_creation")
     */

    public function mairie_projet_creation(Request $request)
    {

        $projet_form = new Projets();
        $form_ProjetForm = $this->createForm(ProjetsType::class, $projet_form);

        $form_ProjetForm->handleRequest($request);
        if( $form_ProjetForm->isSubmitted() && $form_ProjetForm->isValid() ) {

            /** @var UploadedFile $image */
            $image = $form_ProjetForm['image']->getData();
            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($image) {
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $image->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $image->move(
                        $this->getParameter('image_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

				$Username = $this->get('security.token_storage')->getToken()->getUsername();

				if($mairie = $this->getDoctrine()->getRepository(Mairie::class)->findOneByMail($Username) != null)
				{
					$userval = null;
					$villevar = $this->get('security.token_storage')->getToken()->getUser()->getVille();
				}
				else{
					$userval = $this->get('security.token_storage')->getToken()->getUser()->getPseudo();
					$villevar = $this->get('security.token_storage')->getToken()->getUser()->getVilleMairie();
				}

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $projet_form->setImage($newFilename);
                $projet_form->setNbLike(0);
                $projet_form->setUser($userval);
                $projet_form->setVilleMairie($villevar);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($projet_form);
                $entityManager->flush();

                return $this->redirectToRoute('mairie_projet_liste', array('ville' => $villevar));
            }
        }
        $Username = $this->get('security.token_storage')->getToken()->getUsername();

        if($mairie = $this->getDoctrine()->getRepository(Mairie::class)->findOneByMail($Username) != null)
        {
            $villevar = $this->get('security.token_storage')->getToken()->getUser()->getVille();
        }
        else{
            $villevar = $this->get('security.token_storage')->getToken()->getUser()->getVilleMairie();
        }
        return $this->render('projets/creation_projet.html.twig', ['mairie' => $villevar,'formProjetForm' => $form_ProjetForm->createView()]);
    }

	/**
     * @Route("/projets/{ville}", name="mairie_projet_liste")
     */
    public function mairie_projet_liste(string $ville)
    {
        $mairie = $this->getDoctrine()->getRepository(Mairie::class)->findBy(['ville'=>$ville]);
        $projets = $this->getDoctrine()->getRepository(Projets::class)->findBy(['VilleMairie'=>$mairie[0]->getVille()]);

        return $this->render('projets/projets_liste.html.twig', ['mairie'=>$mairie[0]->getVille(), 'projets' => $projets]);
    }

    /**
     * @Route("/projets/{ville}/{projet_name}", name="mairie_projet_detail")
     */
    public function mairie_projet_detail(string  $ville, string $projet_name)
    {
        $mairie = $this->getDoctrine()->getRepository(Mairie::class)->findBy(['ville'=>$ville]);
        $projets = $this->getDoctrine()->getRepository(Projets::class)->findBy(['VilleMairie'=>$mairie[0]->getVille()]);
        $Username = $this->get('security.token_storage')->getToken()->getUsername();

        if($mairie = $this->getDoctrine()->getRepository(Mairie::class)->findOneByMail($Username) != null)
        {
           $message = '';
        }
        else
        {

            $userId = $this->get('security.token_storage')->getToken()->getUser()->getId();
            $taille_projet = sizeof($projets);
            for($i=0;$i<$taille_projet;$i++) {
                $projet_recup = $projets[$i]->getTitre();
                if ($projet_recup == $projet_name) {
                    $projet = $projets[$i];
                    $like_projet = $this->getDoctrine()->getRepository(Like::class)->findBy(['idProjet' => $projet->getId(), 'idUser' => $userId]);
                    if ($like_projet == null) {
                        $message = 'J\'aime ce projet';
                    } else {
                        $message = 'Vous aimez ce projet';
                    }
                }
            }
        }

        $mairie = $this->getDoctrine()->getRepository(Mairie::class)->findBy(['ville'=>$ville]);
        return $this->render('projets/projets_details.html.twig', ['mairie'=>$mairie[0]->getVille(),'projet' => $projet,'message'=>$message]);
    }

    /**
     * @Route("/projets/{ville}/{projet_name}/like", name="mairie_projet_like")
     */
    public function mairie_projet_like(string  $ville, string $projet_name)
    {
        $mairie = $this->getDoctrine()->getRepository(Mairie::class)->findBy(['ville'=>$ville]);
        $projets = $this->getDoctrine()->getRepository(Projets::class)->findBy(['VilleMairie'=>$mairie[0]->getVille()]);
        $Username = $this->get('security.token_storage')->getToken()->getUsername();

        if($mairie = $this->getDoctrine()->getRepository(Mairie::class)->findOneByMail($Username) != null) {
            return $this->redirectToRoute('mairie_projet_detail', ['ville' => $ville, 'projet_name' => $projet_name]);
        }
        else{

            $userId = $this->get('security.token_storage')->getToken()->getUser()->getId();
            $taille_projet = sizeof($projets);
            for($i=0;$i<$taille_projet;$i++)
            {
                $projet_recup = $projets[$i]->getTitre();
                if($projet_recup == $projet_name)
                {
                    $projet = $projets[$i];
                    $like_projet = $this->getDoctrine()->getRepository(Like::class)->findBy(['idProjet'=>$projet->getId(),'idUser'=>$userId]);
                    if($like_projet == null)
                    {
                        $like_click = $projet->getNbLike();
                        $like_click = $like_click + 1;
                        $projet->setNbLike($like_click);
                        $entityManager = $this->getDoctrine()->getManager();
                        $entityManager->persist($projet);
                        $entityManager->flush();
                        $like_bdd = new Like();
                        $like_bdd->setIdProjet($projet->getId());
                        $like_bdd->setIdUser($userId);
                        $entityManager->persist($like_bdd);
                        $entityManager->flush();
                    }

                    else
                    {
                        return $this->redirectToRoute('mairie_projet_detail', ['ville' => $ville, 'projet_name' => $projet_name]);
                    }





                }
            }
        }




        return $this->redirectToRoute('mairie_projet_detail', ['ville' => $ville, 'projet_name' => $projet_name]);
    }
}
