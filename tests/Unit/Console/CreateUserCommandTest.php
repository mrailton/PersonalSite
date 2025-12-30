<?php

declare(strict_types=1);

namespace Tests\Unit\Console;

use App\Console\CreateUserCommand;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class CreateUserCommandTest extends TestCase
{
    public function testCommandCreatesUserSuccessfully(): void
    {
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $capturedUser = null;
        
        $entityManager->expects($this->once())
            ->method('persist')
            ->with($this->callback(function ($user) use (&$capturedUser) {
                $capturedUser = $user;
                return $user instanceof User;
            }));
        
        $entityManager->expects($this->once())
            ->method('flush');

        $command = new CreateUserCommand($entityManager);
        
        $application = new Application();
        $application->addCommands([$command]);
        
        $commandTester = new CommandTester($command);

        $commandTester->setInputs([
            'John Doe',
            'john@example.com',
            'password123',
            'password123',
        ]);

        $commandTester->execute([]);

        $this->assertSame(0, $commandTester->getStatusCode());
        $this->assertStringContainsString('User created successfully!', $commandTester->getDisplay());
        $this->assertStringContainsString('Name: John Doe', $commandTester->getDisplay());
        $this->assertStringContainsString('Email: john@example.com', $commandTester->getDisplay());
        
        $this->assertNotNull($capturedUser);
        $this->assertSame('John Doe', $capturedUser->getName());
        $this->assertSame('john@example.com', $capturedUser->getEmail());
        $this->assertTrue($capturedUser->checkPassword('password123'));
    }

    public function testCommandFailsWhenPasswordsDontMatch(): void
    {
        $entityManager = $this->createMock(EntityManagerInterface::class);
        
        $entityManager->expects($this->never())
            ->method('persist');
        
        $entityManager->expects($this->never())
            ->method('flush');

        $command = new CreateUserCommand($entityManager);
        
        $application = new Application();
        $application->addCommands([$command]);
        
        $commandTester = new CommandTester($command);

        $commandTester->setInputs([
            'John Doe',
            'john@example.com',
            'password123',
            'differentPassword',
        ]);

        $commandTester->execute([]);

        $this->assertSame(1, $commandTester->getStatusCode());
        $this->assertStringContainsString('Passwords do not match!', $commandTester->getDisplay());
    }

    public function testCommandValidatesEmptyName(): void
    {
        $entityManager = $this->createStub(EntityManagerInterface::class);
        $command = new CreateUserCommand($entityManager);
        
        $application = new Application();
        $application->addCommands([$command]);
        
        $commandTester = new CommandTester($command);
        
        $commandTester->setInputs([
            '',
            'John Doe',
            'john@example.com',
            'password123',
            'password123',
        ]);

        $commandTester->execute([]);

        $this->assertStringContainsString('Name cannot be empty', $commandTester->getDisplay());
    }

    public function testCommandValidatesEmptyEmail(): void
    {
        $entityManager = $this->createStub(EntityManagerInterface::class);
        $command = new CreateUserCommand($entityManager);
        
        $application = new Application();
        $application->addCommands([$command]);
        
        $commandTester = new CommandTester($command);
        
        $commandTester->setInputs([
            'John Doe',
            '',
            'john@example.com',
            'password123',
            'password123',
        ]);

        $commandTester->execute([]);

        $this->assertStringContainsString('Email cannot be empty', $commandTester->getDisplay());
    }

    public function testCommandValidatesInvalidEmailFormat(): void
    {
        $entityManager = $this->createStub(EntityManagerInterface::class);
        $command = new CreateUserCommand($entityManager);
        
        $application = new Application();
        $application->addCommands([$command]);
        
        $commandTester = new CommandTester($command);
        
        $commandTester->setInputs([
            'John Doe',
            'invalid-email',
            'john@example.com',
            'password123',
            'password123',
        ]);

        $commandTester->execute([]);

        $this->assertStringContainsString('Invalid email format', $commandTester->getDisplay());
    }

    public function testCommandValidatesPasswordLength(): void
    {
        $entityManager = $this->createStub(EntityManagerInterface::class);
        $command = new CreateUserCommand($entityManager);
        
        $application = new Application();
        $application->addCommands([$command]);
        
        $commandTester = new CommandTester($command);
        
        $commandTester->setInputs([
            'John Doe',
            'john@example.com',
            'short',
            'password123',
            'password123',
        ]);

        $commandTester->execute([]);

        $this->assertStringContainsString('Password must be at least 8 characters', $commandTester->getDisplay());
    }

    public function testCommandValidatesEmptyPassword(): void
    {
        $entityManager = $this->createStub(EntityManagerInterface::class);
        $command = new CreateUserCommand($entityManager);
        
        $application = new Application();
        $application->addCommands([$command]);
        
        $commandTester = new CommandTester($command);
        
        $commandTester->setInputs([
            'John Doe',
            'john@example.com',
            '',
            'password123',
            'password123',
        ]);

        $commandTester->execute([]);

        $this->assertStringContainsString('Password cannot be empty', $commandTester->getDisplay());
    }

    public function testCommandHandlesDatabaseException(): void
    {
        $entityManager = $this->createMock(EntityManagerInterface::class);
        
        $entityManager->expects($this->once())
            ->method('persist');
        
        $entityManager->expects($this->once())
            ->method('flush')
            ->willThrowException(new \Exception('Database error'));

        $command = new CreateUserCommand($entityManager);
        
        $application = new Application();
        $application->addCommands([$command]);
        
        $commandTester = new CommandTester($command);

        $commandTester->setInputs([
            'John Doe',
            'john@example.com',
            'password123',
            'password123',
        ]);

        $commandTester->execute([]);

        $this->assertSame(1, $commandTester->getStatusCode());
        $this->assertStringContainsString('Failed to create user: Database error', $commandTester->getDisplay());
    }
}
