<?php

namespace App\Controller;

use App\Form\UserregisterType;
use App\Form\FormloginType;
use App\Entity\Users;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;


class UserController extends AbstractController
{
    /**
     * @Route("/utilisateur/register", name="register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
		$user = new Users();
		$formregister = $this->createForm(UserregisterType::class, $user);
		$formregister->handleRequest($request);
		if($formregister->isSubmitted() && $formregister->isValid()){

			$hashed_password = password_hash($user->getPassword(), PASSWORD_DEFAULT);
			$user->setPassword($hashed_password);

			$user->setRole('ROLE_USER');
			$entityManager = $this->getDoctrine()->getManager();
			$entityManager->persist($user);
			$entityManager->flush();

			return $this->redirectToRoute('login');
		}

        return $this->render('user/register.html.twig', ['formregister' => $formregister->createView()]);
    }
	/**
     * @Route("/utilisateur/login", name="login")
     */
    public function login(Request $request): Response
    {

		$user = new Users();
		$formlogin = $this->createForm(FormloginType::class, $user);
		$formlogin->handleRequest($request);

		if($formlogin->isSubmitted()){
				$mailform = $user->getMail();
				$passwordform = $user->getPassword();

				if($user = $this->getDoctrine()->getRepository(Users::class)->findOneByMail($mailform)){
					if(password_verify($passwordform, $user->getPassword())){
						$token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
						$this->get('security.token_storage')->setToken($token);
						$this->get('session')->set('_security_main', serialize($token));
						return $this->redirectToRoute('mairie_projet_liste',array('ville'=>$user->getVilleMairie()));
						//echo $this->get('security.token_storage')->getToken()->getUser()->getIdDiscord();
					}
				}

		}


        return $this->render('user/login.html.twig', [
            'controller_name' => 'UserController', 'formlogin' => $formlogin->createView()
        ]);
    }

	/**
     * @Route("/utilisateur/logout", name="logout")
     */
    public function logout(): Response
    {
		$this->get('security.token_storage')->setToken(NULL);
		return $this->redirectToRoute('accueil');
    }
}
