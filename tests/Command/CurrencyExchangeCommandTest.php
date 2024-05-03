<?php
declare(strict_types=1);

namespace App\Tests\Command;

use PHPUnit\Framework\ExpectationFailedException;
use Symfony\Component\Console\Exception\CommandNotFoundException;

class CurrencyExchangeCommandTest extends CommandTestCase
{
    protected static string $commandName = 'rates:get';


    /**
     * @throws CommandNotFoundException
     */
    protected function setUp(): void
    {
        parent::setUp();

        self::execute('rates:import');
    }


    /**
     * @throws ExpectationFailedException
     * @throws CommandNotFoundException
     * @dataProvider data
     */
    public function testCurrencyExchangeCommand(float $amount, string $from, string $to): void
    {
        $input = [
            'amount' => (string)$amount,
            'from' => $from,
            'to' => $to
        ];

        $output = self::execute(self::$commandName, $input);
        $format = "[OK] $amount $from = %d.%d $to";

        self::assertStringMatchesFormat($format, trim($output));
    }


    public static function data(): array
    {
        return [
            [1, 'USD', 'EUR'],
            [2, 'BTC', 'USD'],
            [3, 'EUR', 'USD'],
            [4, 'USD', 'BTC'],
            [5, 'BTC', 'EUR'],
            [6, 'EUR', 'BTC']
        ];
    }
}
