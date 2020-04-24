<?php
//src/Entity/Group.php
declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\Uuid;

class Group
{
    private ?string $id;

    private string $name;

    private User $owner;

    private ?\DateTime $createdAt = null;

    private ?\DateTime $updatedAt = null;

    /** @var Collection|User[] */
    private Collection $users;

    /** @var Collection|Category[] */
    protected ?Collection $categories = null;

    /**
     * @throws \Exception
     */
    public function __construct(string $name, User $owner, string $id = null)
    {
        $this->id = $id ?? Uuid::uuid4()->toString();
        $this->name = $name;
        $this->owner = $owner;
        $this->createdAt = new \DateTime();
        $this->users = new ArrayCollection();
        $this->categories = new ArrayCollection();
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

    public function getOwner(): User
    {
        return $this->owner;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    public function markAsUpdated(): void
    {
        $this->updatedAt = new \DateTime();
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): void
    {
        $this->users->add($user);

        $user->addGroup($this);
    }

    public function removeUser(User $user):void
    {
        $this->users->removeElement($user);
        $user->removeGroup($this);
    }

    public function isOwnedBy(User $user): bool
    {
        return $this->getOwner()->getId() === $user->getId();
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

}