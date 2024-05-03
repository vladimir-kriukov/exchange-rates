<?php

namespace App\Command;

use App\Dto\Exchange;
use App\Services\RatesConverter;
use Brick\Math\Exception\MathException;
use OutOfBoundsException;
use RuntimeException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use TypeError;

use function count;

#[AsCommand(
    name: 'rates:get',
    description: 'Rates get'
)]
class RatesGetCommand extends Command
{
    public function __construct(private readonly ValidatorInterface $validator, private readonly RatesConverter $ratesConverter)
    {
        parent::__construct();
    }

    /**
     * @throws InvalidArgumentException
     */
    protected function configure(): void
    {
        $this
            ->addArgument('amount', InputArgument::REQUIRED, 'Amount')
            ->addArgument('from', InputArgument::REQUIRED, 'From currency')
            ->addArgument('to', InputArgument::REQUIRED, 'To currency');
    }

    /**
     * @throws InvalidArgumentException
     * @throws TypeError|MathException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $amount = $input->getArgument('amount');
        $from = $input->getArgument('from');
        $to = $input->getArgument('to');
        $io = new SymfonyStyle($input, $output);

        try {
            $exchange = new Exchange($from, $to, $amount);
            $this->validate($exchange);
            $result = ($this->ratesConverter)($exchange);
        } catch (RuntimeException $exception) {
            $io->error('Error:');
            $io->text($exception->getMessage());
            $io->text("{$exception->getFile()}:{$exception->getLine()}");

            return Command::FAILURE;
        }

        $io->success("$amount $from = $result $to");

        return Command::SUCCESS;
    }

    /**
     * @throws OutOfBoundsException
     */
    private function validate(Exchange $exchange): void
    {
        $errors = $this->validator->validate($exchange);

        if (count($errors) > 0) {
            throw new OutOfBoundsException((string)$errors);
        }
    }
}
