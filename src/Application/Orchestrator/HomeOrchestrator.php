<?php

/**
 * Este archivo pertenece a la aplicación XYZ.
 * Todos los derechos reservados.
 *
 * @category Controlador
 * @package  App\Application\Orchestrator\HomeOrchestrator
 * @license  Todos los derechos reservados
 */

declare(strict_types=1 );

namespace App\Application\Orchestrator;

use App\Domain\Model\Usuario;
use App\Infrastructure\Persistence\Doctrine\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class HomeOrchestrator
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
        // Crear un nuevo usuario
        $user = new Usuario();
        $user->setEmail('t3e33ssst@example.com');
        $plaintextPassword = 'zzz';
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