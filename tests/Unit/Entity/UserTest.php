<?php

declare(strict_types=1);

namespace Tests\Unit\Entity;

use App\Entity\User;
use DateTime;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testConstructorSetsCreatedAt(): void
    {
        $user = new User();
        
        $this->assertInstanceOf(DateTime::class, $user->getCreatedAt());
    }
    
    public function testCanSetAndGetName(): void
    {
        $user = new User();
        $user->setName('John Doe');
        
        $this->assertSame('John Doe', $user->getName());
    }
    
    public function testCanSetAndGetEmail(): void
    {
        $user = new User();
        $user->setEmail('john@example.com');
        
        $this->assertSame('john@example.com', $user->getEmail());
    }
    
    public function testPasswordIsHashedWhenSet(): void
    {
        $user = new User();
        $plainPassword = 'mySecretPassword123';
        
        $user->setPassword($plainPassword);
        
        $this->assertTrue($user->checkPassword($plainPassword));
    }
    
    public function testCheckPasswordReturnsFalseForWrongPassword(): void
    {
        $user = new User();
        $user->setPassword('correctPassword');
        
        $this->assertFalse($user->checkPassword('wrongPassword'));
    }
    
    public function testCheckPasswordReturnsTrueForCorrectPassword(): void
    {
        $user = new User();
        $password = 'myPassword123';
        $user->setPassword($password);
        
        $this->assertTrue($user->checkPassword($password));
    }
    
    public function testSetNameReturnsUserInstance(): void
    {
        $user = new User();
        
        $this->assertSame($user, $user->setName('Jane Doe'));
    }
    
    public function testSetEmailReturnsUserInstance(): void
    {
        $user = new User();
        
        $this->assertSame($user, $user->setEmail('jane@example.com'));
    }
    
    public function testSetPasswordReturnsUserInstance(): void
    {
        $user = new User();
        
        $this->assertSame($user, $user->setPassword('password123'));
    }
}
