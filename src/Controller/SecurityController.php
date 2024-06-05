<?php

namespace App\Controller;

use App\Form\ForgottenPasswordType;
use App\Form\ResetPasswordType;
use App\Repository\UsersRepository;
use App\Services\JWTService;
use App\Services\SendEmailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

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




    #[Route(path: '/mot-de-passe-oublie', name: 'app_forgotten_password')]
    public function forgottenPassword(
        UsersRepository $usersRepository, 
        Request $request,
        JWTService $jwt,
        SendEmailService $mail
        ): Response
    {
        $form = $this->createForm(ForgottenPasswordType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) 
        {
            $user = $usersRepository->findOneByEmail($form->get('email')->getData());

            if($user)
            {
                // dd($user[0]->getId());
                // envoie un mail
                // Token generator
                $header = [
                    'alg' => 'HS256',
                    'typ' => 'JWT'
                ];

                $payload = [
                    'user_id' => $user[0]->getId()
                ];

                // dd($this->getParameter('app.jwtsecret'));

                $token = $jwt->generate($header, $payload, $this->getParameter('app.jwtsecret'));

                // $token = md5($token);

                // Generate URL to reset_password
                $url = $this->generateUrl('app_reset_password', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);

                // Send email
                $mail->send(
                    'no-reply@snowtricks.com',
                    $user[0]->getEmail(),
                    'Récupération de mot de passe sur le site SnowTricks',
                    'reset_password_email',
                    compact('user', 'url')
                );

                $this->addFlash('success', 'L\'email de récupération a été envoyé');
                return $this->redirectToRoute('app_login');

            }
            else
            {
                $this->addFlash('danger', 'Une erreur est survenue');
            }
        }

        return $this->render('security/forgotten_pasword.html.twig', [
            'passForm' => $form
        ]);
    }




    #[Route(path: '/reset-password/{token}', name: 'app_reset_password', requirements: ['token' => '.+'])]
    public function resetPassword(
        string $token, 
        JWTService $jwt,
        UsersRepository $usersRepository,
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $em
        ): Response
    {
        $test = 1;
        if($jwt->isValid($token) && !$jwt->isExpired($token) && $jwt->check($token, $this->getParameter('app.jwtsecret')))
        {
            $payload = $jwt->getPayload($token);

            $user = $usersRepository->findOneByID($payload['user_id']);
            // dd($user);

            if($user)
            {
                $user = $user[0];
                $form = $this->createForm(ResetPasswordType::class);
                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) 
                {
                    $user->setPassword(
                        $passwordHasher->hashPassword($user, $form->get('password')->getData())
                    );

                    $em->flush();

                    $this->addFlash('success', 'Le mot de passe a été modifé !');
                    return $this->redirectToRoute('app_login');
                }
            }
        }
        else
        {
            $this->addFlash('danger', 'Le token est invalide ou a expiré');
            return $this->redirectToRoute('app_login');
        }
        
        return $this->render('security/reset_pasword.html.twig', [
            'resetForm' => $form
        ]);
    }
}
