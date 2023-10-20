<?php

/**
 * Este archivo pertenece a la aplicación XYZ.
 * Todos los derechos reservados.
 *
 * @category Controlador
 * @package  App\Infrastructure\Controller
 * @license  Todos los derechos reservados
 */

declare(strict_types=1);

namespace App\Infrastructure\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Security\EmailVerifier;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Infrastructure\Persistence\Doctrine\Repository\UserRepository;
use App\Domain\Model\Usuario;

final class UserController extends AbstractController
{
    private EmailVerifier $emailVerifier;
    private UserRepository $userRepository;

    public function __construct(EmailVerifier $emailVerifier, UserRepository $userRepository)
    {
        $this->emailVerifier = $emailVerifier;
        $this->userRepository = $userRepository;
    }

    public function loginAction(Request $request, AuthenticationUtils $authenticationUtils, EmailVerifier $emailVerifier): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('panel');
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('/User/login.html.twig', [
            'last_username'=> $lastUsername,
            'error'=> $error,
        ]);
    }

    public function registerUsersAction(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserRepository $userRepository): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('panel');
        }

        $user = new Usuario();

        $submittedToken = $request->request->get('token');

        if ($request->isMethod('POST') && $this->isCsrfTokenValid('validateTokenSym', $submittedToken) ) {

            $datosForm = $request->request->all();
            $userEmailCheck = $this->userRepository->findOneBy(array('email' => $datosForm['email']));
            
            if($userEmailCheck){
                $this->addFlash(
                    'error',
                    'Este email ya está registrado'
                );
                return $this->render('/Registration/register.html.twig');
            }

            if(null == $datosForm['email'] && null == $datosForm['plainPassword'] ){
                $this->addFlash(
                    'error',
                    'Rellena todos los campos.'
                );
                return $this->render('/Registration/register.html.twig');
            }

            $user->setRoles(['ROLE_USER']);
            $user->setEmail($datosForm['email']);
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $datosForm['plainPassword']
                )
            );

            $this->userRepository->save($user, true);

            $this->emailVerifier->sendEmailConfirmation(
                'app_verify_email',
                $user,
                (new TemplatedEmail())
                    ->from(new Address('mail@mail.er', 'luis'))
                    ->to($datosForm['email'])
                    ->subject('Please Confirm your Email')
                    ->htmlTemplate('Registration/confirmation_email.html.twig')
            );

            return $this->redirectToRoute('panel');
        }

        return $this->render('/Registration/register.html.twig');
    }

    public function verifyUserEmailAction(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        try {
            $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $exception->getReason());

            return $this->redirectToRoute('app_register');
        }

        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('app_register');
    }

    public function cuentaUsuarioControllerAction(Request $request,  UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $title = 'Edita tu Cuenta de Usuario';
        $userEmail = $this->getUser()->getEmail();

        if ($request->isMethod('POST')) {
            $datosForm = $request->request->all();
            $user = $this->userRepository->findOneBy(array('id' => $this->getUser()->getId()));
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $datosForm['plainPassword']
                )
            );
            $this->userRepository->save($user, true);

            $this->addFlash(
                'sucess',
                'Contraseña cambiada correctamente.'
            );
        }

        return $this->render('/Panel/Sections/panelUsuario.html.twig', [
            'title'=>$title,
            'email'=> $userEmail, 
        ]);
    }
    public function recoveryAccountUser(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserRepository $userRepository): Response
    {
              return $this->render('/User/recoveryPassword.html.twig');
    }
    public function logoutAction(): void
    {
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }

}
