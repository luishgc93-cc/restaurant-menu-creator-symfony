<?php

namespace App\Domain\Model;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use App\Persistence\Doctrine\Repository\UserRepository;

/**
 * @ORM\Entity
 * @ORM\Table(name="usuario")
 */
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class Usuario implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity="App\Domain\Model\Local", mappedBy="usuario")
     */
    private $locales;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isVerified = false;
    
    /**
     * @ORM\OneToMany(targetEntity="App\Domain\Model\UsuarioRecovery", mappedBy="usuario", cascade={"persist"})
     */
    private $recoveryTokens;
    
    /**
     * @ORM\OneToMany(targetEntity="App\Domain\Model\UsuarioDeleteAccount", mappedBy="usuario", cascade={"persist"})
     */
    private $usuarioDeleteAccount;

    public function __construct()
    {
        $this->locales = new ArrayCollection();
        $this->recoveryTokens = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
    }

    /**
     * @return Collection|Local[]
     */
    public function getLocales(): Collection
    {
        return $this->locales;
    }

    public function addLocal(Local $local): self
    {
        if (!$this->locales->contains($local)) {
            $this->locales[] = $local;
            $local->setUsuario($this);
        }

        return $this;
    }

    public function removeLocal(Local $local): self
    {
        if ($this->locales->contains($local)) {
            $this->locales->removeElement($local);
            // set the owning side to null (unless already changed)
            if ($local->getUsuario() === $this) {
                $local->setUsuario(null);
            }
        }

        return $this;
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function addRecoveryToken(UsuarioRecovery $recoveryToken)
    {
    $this->recoveryTokens[] = $recoveryToken;
    $recoveryToken->setUsuario($this);

    return $this;
    }

    public function getRecoveryToken()
    {
        return $this->recoveryTokens;
    }

    public function getUsuarioDeleteAccount()
    {
        return $this->usuarioDeleteAccount;
    }

    public function setUsuarioDeleteAccount($usuarioDeleteAccount): self
    {
        $this->usuarioDeleteAccount = $usuarioDeleteAccount;

        return $this;
    }

    public function addUsuarioDeleteAccount(UsuarioDeleteAccount $usuarioDeleteAccount)
    {
    $this->usuarioDeleteAccount[] = $usuarioDeleteAccount;
    $usuarioDeleteAccount->setUsuario($this);

    return $this;
    }
}
