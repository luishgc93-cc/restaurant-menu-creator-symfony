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

use App\Domain\Model\UsuarioRecovery;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Infrastructure\Persistence\Doctrine\Repository\UserRepository;
use App\Domain\Model\Usuario;
use Symfony\Component\Uid\Uuid;
use Doctrine\ORM\EntityManagerInterface;

final class UserController extends AbstractController
{
    const TEXT_EMAIL_VERIFICATION_DEFAULT = 'Revisa tu Buzón de Email para recuperar su contraseña.';
    private UserRepository $userRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(UserRepository $userRepository,  EntityManagerInterface $entityManager)
    {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
    }

    public function loginAction(Request $request, AuthenticationUtils $authenticationUtils): Response
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
           
            $invalidRegisterUser = $this->registerAlertsForInvalidEmailUser($userEmailCheck, $datosForm);        
            
            if('' !== $invalidRegisterUser) {
                $this->addFlash(
                    'error',
                    $invalidRegisterUser
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
            $this->addFlash(
                'sucess',
                'Cuenta creada correctamente, comprueba tu Email para validarlo.'
            );
            return $this->redirectToRoute('panel');
        }

        return $this->render('/Registration/register.html.twig');
    }

    private function registerAlertsForInvalidEmailUser(?Usuario $userEmailCheck, array $dataForm): string
    {
        if($userEmailCheck){
            return 'Este email ya está registrado.';
        }

        if (!filter_var($dataForm['email'], FILTER_VALIDATE_EMAIL)) {
            return 'Introduzca un email válido.';
        }

        $password = $dataForm['plainPassword'];
        $uppercase = preg_match('@[A-Z]@', $password);
        $lowercase = preg_match('@[a-z]@', $password);
        $number    = preg_match('@[0-9]@', $password);

        if(!$uppercase || !$lowercase || !$number || strlen($password) < 6) {
            return 'La contraseña debe tener al menos 6 caracteres y debe incluir al menos una letra mayúscula, 
            un número.';
        }

        return '';
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
    public function recoveryAccountUser(Request $request, String $alertMessage = self::TEXT_EMAIL_VERIFICATION_DEFAULT): Response
    {
        $submittedToken = $request->request->get('token');

        if ($request->isMethod('POST') && $this->isCsrfTokenValid('validateTokenSym', $submittedToken)) {
            $datosForm = $request->request->all();
            $emailForm = $request->getPathInfo() === '/login/recuperar-cuenta' ? 'emailParaRecuperarContrasena' : 'username';
            $userEmailCheck = $this->userRepository->findOneBy(array('email' => $datosForm[$emailForm]));
            if($userEmailCheck){
                $uuid = Uuid::v4();
                $usuarioRecovery = $userEmailCheck->getRecoveryToken()->first() ?? '';
                if(!$usuarioRecovery){
                    $usuarioRecovery = new UsuarioRecovery();
                }
                $usuarioRecovery->setUsuario($userEmailCheck);
                $usuarioRecovery->setPin($uuid->__toString());
                $dateTime = new \DateTime('now');
                $usuarioRecovery->setFechaExpiracionPin($dateTime->modify('+1 day'));
                $userEmailCheck->addRecoveryToken($usuarioRecovery);
                $this->userRepository->save($userEmailCheck, true);
                $this->addFlash('sucess', $alertMessage);
                return $this->redirectToRoute('app_login');
            }

        }
        return $this->render('/User/recoveryPassword.html.twig');
    }
    
    public function recoveryAccountUserValidateToken(Request $request, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $tokenQuery = $request->query->get('token');
        $datosForm = $request->request->all();

        if($tokenQuery){
            $token = $this->entityManager->getRepository(UsuarioRecovery::class)->findOneBy(['pin' => $tokenQuery]);
            $password = $datosForm['password'] ?? null;
            
            if( (bool) ($token->getFechaExpiracion() < new \DateTime('now')) ){
                $this->addFlash(
                    'error',
                    'El enlace ha caducado, reenvía de nuevo la solicitud de restablecimiento de contraseña.'
                );
                return $this->redirectToRoute('recoveryAccountUser');
            }

            if($tokenQuery && $password){
                $user = $this->userRepository->findOneBy(array('id' => $token->getUsuario()->getId()));
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $datosForm['password']
                    )
                );
                $this->entityManager->remove($token);
                $this->userRepository->save($user, true);
                $this->addFlash(
                    'sucess',
                    'Contraseña restablecida correctamente.'
                );
                return $this->redirectToRoute('app_login');
            }
        }
        return $this->render('/User/recoveryPasswordForReset.html.twig');
    }

    public function verifyUserEmailAction(Request $request): Response
    {
        $tokenQuery = $request->query->get('token');
        if($tokenQuery){
            $token = $this->entityManager->getRepository(UsuarioRecovery::class)->findOneBy(['pin' => $tokenQuery]);
            $usuario = $token->getUsuario();
            $usuario->setIsVerified(true);
            $this->entityManager->remove($token);
            $this->userRepository->save($usuario, true);
            $this->addFlash('sucess', 'Email verificado correctamente.');
            return $this->redirectToRoute('panel');
        }
        return $this->render('/User/recoveryPassword.html.twig');
    }
    public function logoutAction(): void
    {
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }
    public function confirmationDeleteAccountUserControllerAction(Request $request): Response
    {
        $title = 'Confirmar borrado de cuenta de Usuario';

        $submittedToken = $request->request->get('token');

        if ($request->isMethod('POST') && $this->isCsrfTokenValid('validateTokenSym', $submittedToken)) {
            //ENVIAR EMAIL 
            $this->addFlash('sucess', 'Comrpruebe su Email en unos minutos para verificar su baja de nuestra plataforma.');
            return $this->redirectToRoute('panel');
        }

        return $this->render('/Panel/Sections/confirmationDeleteAccount.html.twig', [
            'title'=>$title
        ]);
    }
}
