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
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class UserOrchestrator
{
    private $entityManager;
    private $passwordHasher;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher)
    {
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
    }
    

    public function getAllUsers()
    {
        // Crear un nuevo usuario
        $user = new Usuario();
        $user->setEmail('test@example.com');
        $plaintextPassword = 'zzz';
        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            $plaintextPassword
        );
        $user->setPassword($hashedPassword);
        $user->setRoles(['ROLE_USER']);

        // Persistir el usuario en la base de datos
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    
        // Verificación de registro exitoso
        return 'Usuario registrado exitosamente.';
    }
    
}