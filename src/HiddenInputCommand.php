<?php
declare(strict_types=1);
namespace Helhum\SfTest;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class HiddenInputCommand extends Command
{
    protected function configure()
    {
        $this->setName('hidden:input');
        $this->setDefinition(
            [
                new InputArgument(
                    'password',
                    InputArgument::REQUIRED,
                    'Password'
                ),
                new InputArgument(
                    'action',
                    InputArgument::REQUIRED,
                    'action'
                ),
            ]
        );
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        if (empty($input->getArgument('password'))) {
            $password = $io->askHidden(
                'Password',
                function ($password) {
                    if ($error = $this->validatePassword($password)) {
                        throw new ArgumentValidationFailedException($error);
                    }

                    return $password;
                }
            );
            $input->setArgument('password', $password);
        }
        if (empty($input->getArgument('action'))) {
            $action = $io->ask(
                'What do you want to do',
                null,
                function ($action) {
                    if ($error = $this->validateAction($action)) {
                        throw new ArgumentValidationFailedException($error);
                    }

                    return $action;
                }
            );
            $input->setArgument('action', $action);
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($input->getArgument('password') === 'friend') {
            $output->writeln('Door opens');
            return 0;
        }
        return 1;
    }

    private function validatePassword(?string $password): ?string
    {
        if (empty($password)) {
            return 'Password must not be empty.';
        }
        if (strlen($password) < 5) {
            return 'Password must have at least 5 characters.';
        }

        return null;
    }

    private function validateAction(?string $action): ?string
    {
        if (empty($action)) {
            return 'Action must not be empty.';
        }

        return null;
    }
}
