<?php

declare(strict_types=1);

namespace App\Entity;

use App\Security\Role;
use Ramsey\Uuid\Uuid;

class User
{
    protected ?string $id;
    protected string $name;
    protected string $email;
    protected string $password;
    protected array $roles;
    protected \DateTime $createdAt;
    protected \DateTime $updateAt;

    /**
     * @throws \Exeeption
     */
    public function __construct(string $name, string $email, string $id = null)
    {
        $this->id = $id ?? Uuid::uuid4()->toString();
        $this->name = $name;
        $this->email = $email;
        $this->roles[] = Role::ROLE_USER;
        $this->createdAt = new \DateTime();
        $this->markAsUpdated();
    }
    
    public function getId(): ?string
    {
        return $this->id;
    }
    
    public function getName():string
    {
        return $this->name;
    }
    
    public function setName(string $name):void
    {
        $this->name = $name;
    }

    public function getEmail():string
    {
        return $this->email;
    }
    
    public function setEmail(string $email):void
    {
        $this->email = $email;
    }    

    public function getPassword():string
    {
        return $this->password;
    }
    
    public function setPassword(string $password):void
    {
        $this->password = $password;
    }    
    
    public function getRoles():array
    {
        return $this->roles;
    }

    public function getCreatedAt(): \Datetime
    {
        return $this->createdAt;
    }
    
    public function getUpdatedAt(): \Datetime
    {
        return $this->UpdatedAt;
    }

    public function markAsUpdated(): void
    {
        $this->updatedAt = new \DateTime();
    }

}
