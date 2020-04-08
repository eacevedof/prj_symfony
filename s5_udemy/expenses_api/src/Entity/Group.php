<?php
//src/Entity/Group.php
declare(strict_types=1);
namespace App\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use phpDocumentor\Reflection\Types\Collection;
use Ramsey\Uuid\Uuid;

class Group
{
    private ?string $id;
    private string $name;
    private User $owner;
    protected \DateTime $createdAt;
    protected \DateTime $updatedAt;

    /** @var Collection|User[  */
    private Collection $users;

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

    }

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return User
     */
    public function getOwner(): User
    {
        return $this->owner;
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

    /**
     * @return Collection|User[
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): void
    {
        //relación n:m
        $this->users->add($user);
        $user->addGroup($this);
    }

}