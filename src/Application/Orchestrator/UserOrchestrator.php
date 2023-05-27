<?php

/**
 * Este archivo pertenece a la aplicación XYZ.
 * Todos los derechos reservados.
 *
 * @category Controlador
 * @package  App\Application\Orchestrator\UserOrchestrator
 * @license  Todos los derechos reservados
 */

declare(strict_types=1 );

namespace App\Application\Orchestrator;

use App\Domain\Model\Usuario;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Infrastructure\Persistence\Doctrine\Repository\UserRepository;

final class UserOrchestrator
{
    private $userRepository;
    private $passwordHasher;

    public function __construct(UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher)
    {
        $this->userRepository = $userRepository;
        $this->passwordHasher = $passwordHasher;
    }
    

    public function getAllUsers()
    {
        
        $user = new Usuario();
        $user->setEmail('a@a.com');
        $plaintextPassword = 'a';
        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            $plaintextPassword
        );
        $user->setPassword($hashedPassword);
        $user->setRoles(['ROLE_USER']);

        $this->userRepository->save($user, true);
            
        // Verificación de registro exitoso
        return 'Usuario registrado exitosamente.';
    }
    
}