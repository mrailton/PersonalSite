<?php

declare(strict_types=1);

namespace App\Entity;

use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\UniqueConstraint;

#[Entity, Table(name: 'users', uniqueConstraints: [new UniqueConstraint(name: 'email_unique', columns: ['email'])])]
class User
{
    #[Id]
    #[GeneratedValue]
    #[Column(name: 'id', type: Types::INTEGER)]
    private int $id;

    #[Column(name: 'name', type: Types::STRING, length: 255, nullable: false)]
    private string $name;

    #[Column(name: 'email', type: Types::STRING, length: 255, nullable: false)]
    private string $email;

    #[Column(name: 'password', type: Types::STRING, length: 255, nullable: false)]
    private string $password;

    #[Column(name: 'created_at', type: Types::DATETIME_MUTABLE, nullable: false)]
    private DateTime $createdAt;

    public function __construct()
    {
        $this->createdAt = new DateTime();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }
    
    public function setPassword(string $password): self
    {
        $this->password = password_hash($password, PASSWORD_DEFAULT);

        return $this;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function checkPassword(string $attempt): bool
    {
        return password_verify($attempt, $this->password);
    }
}