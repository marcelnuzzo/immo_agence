<?php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields={"username"}, message="There is already an account with this username")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min=2, minMessage = "Votre mot de passe doit faire minimun 2 caractères")
     */
    private $username;
    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email()
     */
    private $email;
    
    // /**
    // * @ORM\Column(type="string", length=255)
    // */
    // private $roles;
    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min=2, minMessage = "Votre mot de passe doit faire minimun 2 caractères")
     * @Assert\EqualTo(propertyPath="confirm_password")
     */
    private $password;
    /**
     * @Assert\EqualTo(propertyPath="password", message = "le mot de passe doit être le même que dans password")
     */
    public $confirm_password;
    public function getId(): ?int
    {
        return $this->id;
    }
    public function getUsername(): ?string
    {
        return $this->username;
    }
    public function setUsername(string $username): self
    {
        $this->username = $username;
        return $this;
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
    /*
    public function getRoles(): ?string
    {
        return $this->roles;
    }
    public function setRoles(string $roles): self
    {
        $this->roles = $roles;
        return $this;
    }
    */
    public function getPassword(): ?string
    {
        return $this->password;
    }
    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }
    
    public function getRoles()
    {
        if (empty($this->roles)) {
            return ['ROLE_USER'];
        }
        return $this->roles;
    }
    
    /*
    public function setRoles(string $roles): self
    {
        $this->roles = $roles;
        return $this;
    }
    */
    
    /*
    public function getRoles()
    {
        return ['ROLE_USER',
                'ROLE_ADMIN'
        ];
    }  
    */
    
    /**
     * eraseCredentials
     *
     * @return void
     */
    public function eraseCredentials()
    {
        
    }
    /**
     * getSalt
     *
     * @return string | null
     */
    public function getSalt()
    {
        return null;
    }
      
          
    /** @see \Serializable::serialize() */
        public function serialize()
        {
            return serialize([
                $this->id,
                $this->username,
                $this->password,
                $this->confirm_password,
                // see section on salt below
                // $this->salt,
            ]);
        }
      
       
        /**
         * unserialize
         *
         * @param  mixed $serialized
         *
         * @return void
         */
        public function unserialize($serialized)
        {
            list (
                $this->id,
                $this->username,
                $this->password,
                $this->confirm_password,
                // see section on salt below
                // $this->salt
            ) = unserialize($serialized, ['allowed_classes' => false]);
        }
}