<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Entity\PasswordUpdate;
use App\Form\PasswordUpdateType;
use App\Repository\UserRepository;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/user")
 * @isGranted("ROLE_USER")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="user_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="user_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_delete", methods={"DELETE"})
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_index');
	}
	
	/**
	 * Mise à jour du mot de passe
	 * 
	 * @Route ("/user/updatepwd", name="user_updatepwd")
	 * @isGranted("ROLE_USER")
	 * 
	 * @return Response
	 */
	public function update_password(Request $request, UserPasswordEncoderInterface $encoder){

		$passwordUpdate = new PasswordUpdate();
		$user = $this->getUser();

		
		$form = $this->createForm(PasswordUpdateType::class, $passwordUpdate );
		
		$form->handleRequest($request);
		
		if ($form->isSubmitted() && $form->isValid()){

			// 1- vérif de l'ancien mot de passe

			$isPwdValid = $encoder->isPasswordValid($user, $passwordUpdate->getOldPassword());

			if ( ! $isPwdValid ){
			
				// if (!password_verify($passwordUpdate->getOldPassword(), $user->getPassword())){
				// return $this->passwordEncoder->isPasswordValid($user, $credentials['password']);

				// gérer l'erreur
				$form->get('oldPassword')->addError(new FormError("Vous n'avez pas saisi correctement votre mot de passe actuel !"));

			}
			else {
				$newPassword = $passwordUpdate->getNewPassword();

				$hash = $encoder->encodePassword($user, $newPassword);
				$user->setPassword($hash);

				$entityManager = $this->getDoctrine()->getManager();
				$entityManager->persist($user);
				$entityManager->flush();
	
				$this->addFlash(
					"success",
					"Votre mot de passe a bien été modifié"
				);

				return $this->redirectToRoute('taxon_index', [
					// 'slug' => $user->getSlug(),
					// 'id' => $user->getId()
					]);
					
				dd($user);
			}

		}

		return $this->render('user/updatepwd.html.twig', [
			'form' => $form->createView()
		]);
	}


}
