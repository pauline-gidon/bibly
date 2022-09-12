<?php

namespace App\Controller;

use App\Form\MagicLinkUserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Http\LoginLink\LoginLinkHandlerInterface;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
         if ($this->getUser()) {
             return $this->redirectToRoute('index');
         }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route(path: '/magic', name: 'magic')]
    public function magic(Request $request, loginLinkHandlerInterface $loginLinkHandler, MailerInterface $mailer, UserRepository $userRepository): Response
    {
        // formulaire
        $form = $this->createForm(MagicLinkUserType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $userRepository->find($form->getData()->getEmail());
            dd($user);


        }
//        $loginlinkDetails = $loginLinkHandler->createLoginLink($user);
//        $email = (new Email())
//            ->from('bot@test.com')
//            ->to($user->getEmail())
//            ->subject('Magic link')
//            ->text('Your magic link is : '.$loginlinkDetails->getUrl());
//        $mailer->send($email);

        return $this->render('security/magic.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
