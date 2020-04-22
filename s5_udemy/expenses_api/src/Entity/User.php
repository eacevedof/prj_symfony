<?php
//src/Entity/User.php
declare(strict_types=1);

namespace App\Entity;

use App\Security\Roles;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface
{
    protected ?string $id;
    protected string $name;
    protected string $email;
    protected string $password;
    protected array $roles;
    protected ?\DateTime $createdAt = null;
    protected ?\DateTime $updatedAt = null;

    /** @var Collection|Group[] */
    protected ?Collection $groups = null;

    /**
     * @throws \Exception
     */
    public function __construct(string $name, string $email, string $id = null)
    {
        $this->id = $id ?? Uuid::uuid4()->toString();
        $this->name = $name;
        $this->email = $email;
        $this->roles[] = Roles::ROLE_USER;
        $this->createdAt = new \DateTime();
        $this->groups = new ArrayCollection();
        $this->markAsUpdated();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    } 

    public function getCreatedAt(): \Datetime
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): \Datetime
    {
        return $this->updatedAt;
    }

    public function markAsUpdated(): void
    {
        $this->updatedAt = new \DateTime();
    }

    public function getSalt(): void
    {
    }

    public function getUsername(): string
    {
        return $this->email;
    }

    public function eraseCredentials(): void
    {
    }

    //para acceder desde voter a tus propios datos
    public function equals(User $user): bool
    {
        return $this->getId() == $user->getId();
    }

    /**
     * @return Group[]|Collection
     */
    public function getGroups():Collection
    {
        return $this->groups;
    }

    public function addGroup(Group $group): void
    {
        $this->groups->add($group);
    }

    public function removeGroup(Group $group): void
    {
        $this->groups->removeElement($group);
    }

}// User
