<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Mairie;
use App\Entity\Projets;
use App\Form\ProjetsType;
use App\Form\MairieType;
use App\Form\FormloginmairieType;
use App\Form\MairieRegisterType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class MairieController extends AbstractController
{

    /**
     * @Route("/mairie/login", name="mairie_login")
     */
    public function mairie_login(Request $request): Response
    {

		$mairie = new Mairie();
		$formlogin = $this->createForm(FormloginmairieType::class, $mairie);
		$formlogin->handleRequest($request);

		if($formlogin->isSubmitted()){
				$mailform = $mairie->getMail();
				$passwordform = $mairie->getPassword();

				if($mairie = $this->getDoctrine()->getRepository(Mairie::class)->findOneByMail($mailform)){
					if(password_verify($passwordform, $mairie->getPassword())){
						$token = new UsernamePasswordToken($mairie, null, 'main', $mairie->getRoles());
						$this->get('security.token_storage')->setToken($token);
						$this->get('session')->set('_security_main', serialize($token));
						return $this->redirectToRoute('mairie_projet_liste',array('ville'=>$mairie->getVille()));
						//echo $this->get('security.token_storage')->getToken()->getUser()->getIdDiscord();
					}
				}

		}


        return $this->render('mairie/login.html.twig', [
            'formMairieLogin' => $formlogin->createView()
        ]);
    }

    /**
     * @Route("/mairie/register", name="mairie_register")
     */
    public function mairie_register(Request $request)
    {
        $mairie_form = new Mairie();
        $form_MairieRegister = $this->createForm(MairieRegisterType::class, $mairie_form);

        $form_MairieRegister->handleRequest($request);
        if( $form_MairieRegister->isSubmitted() && $form_MairieRegister->isValid() )
        {

            $hashed_password = password_hash($mairie_form->getPassword(), PASSWORD_DEFAULT);
            $mairie_form->setPassword($hashed_password);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($mairie_form);
            $entityManager->flush();

            return $this->redirectToRoute('mairie_login');
        }

        return $this->render('mairie/register.html.twig', ['form_MairieRegister' => $form_MairieRegister->createView()]);

    }

}
