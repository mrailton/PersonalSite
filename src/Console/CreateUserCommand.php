<?php

declare(strict_types=1);

namespace App\Console;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class CreateUserCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('user:create')
            ->setDescription('Create a new user');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var QuestionHelper $helper */
        $helper = $this->getHelper('question');

        $nameQuestion = new Question('Enter the user\'s name: ');

        $nameQuestion->setValidator(function ($answer) {
            if (!is_string($answer) || empty(trim($answer))) {
                throw new RuntimeException('Name cannot be empty');
            }

            return $answer;
        });

        $name = $helper->ask($input, $output, $nameQuestion);

        if (!is_string($name)) {
            throw new RuntimeException('Name must be a string');
        }

        $emailQuestion = new Question('Enter the user\'s email: ');

        $emailQuestion->setValidator(function ($answer) {
            if (!is_string($answer) || empty(trim($answer))) {
                throw new RuntimeException('Email cannot be empty');
            }
            if (!filter_var($answer, FILTER_VALIDATE_EMAIL)) {
                throw new RuntimeException('Invalid email format');
            }
            return $answer;
        });

        $email = $helper->ask($input, $output, $emailQuestion);

        if (!is_string($email)) {
            throw new RuntimeException('Email must be a string');
        }

        $passwordQuestion = new Question('Enter the user\'s password: ');
        $passwordQuestion->setHidden(true);
        $passwordQuestion->setHiddenFallback(false);

        $passwordQuestion->setValidator(function ($answer) {
            if (!is_string($answer) || empty($answer)) {
                throw new RuntimeException('Password cannot be empty');
            }

            if (strlen($answer) < 8) {
                throw new RuntimeException('Password must be at least 8 characters');
            }

            return $answer;
        });

        $password = $helper->ask($input, $output, $passwordQuestion);

        if (!is_string($password)) {
            throw new RuntimeException('Password must be a string');
        }

        $confirmQuestion = new Question('Confirm password: ');
        $confirmQuestion->setHidden(true);
        $confirmQuestion->setHiddenFallback(false);
        $confirm = $helper->ask($input, $output, $confirmQuestion);

        if (!is_string($confirm)) {
            $confirm = '';
        }

        if ($password !== $confirm) {
            $output->writeln('<error>Passwords do not match!</error>');
            return Command::FAILURE;
        }

        $user = new User();
        $user->setName($name);
        $user->setEmail($email);
        $user->setPassword($password);

        try {
            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $output->writeln('<info>User created successfully!</info>');
            $output->writeln(sprintf('Name: %s', $user->getName()));
            $output->writeln(sprintf('Email: %s', $user->getEmail()));

            return Command::SUCCESS;
        } catch (Exception $e) {
            $output->writeln('<error>Failed to create user: ' . $e->getMessage() . '</error>');
            return Command::FAILURE;
        }
    }
}
