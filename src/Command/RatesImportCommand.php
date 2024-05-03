<?php

declare(strict_types=1);

namespace App\Command;

use App\Services\RatesUpdater;
use Brick\Math\Exception\MathException;
use RuntimeException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'rates:import',
    description: 'Import exchange rates',
)]
class RatesImportCommand extends Command
{
    public function __construct(private readonly RatesUpdater $ratesUpdater)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {
            ($this->ratesUpdater)();
            $io->text('Rates loaded');
        } catch (RuntimeException|MathException $exception) {
            $io->error('Error:');
            $io->text($exception->getMessage());
            $io->text("{$exception->getFile()}:{$exception->getLine()}");

            return Command::FAILURE;
        }

        $io->success('Rates have been updated');

        return Command::SUCCESS;
    }
}
