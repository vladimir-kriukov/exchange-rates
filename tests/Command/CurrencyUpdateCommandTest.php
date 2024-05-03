<?php
declare(strict_types=1);

namespace App\Tests\Command;

use PHPUnit\Framework\ExpectationFailedException;
use Symfony\Component\Console\Exception\CommandNotFoundException;
use Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException;

class CurrencyUpdateCommandTest extends CommandTestCase
{
    protected static string $commandName = 'rates:import';


    /**
     * @throws CommandNotFoundException
     * @throws ExpectationFailedException
     * @throws ServiceCircularReferenceException
     */
    public function testCurrencyUpdateCommand(): void
    {
        $output = self::execute(self::$commandName);

        self::assertStringContainsString('Rates loaded', $output);
        self::assertStringContainsString('Rates have been updated', $output);
    }
}
