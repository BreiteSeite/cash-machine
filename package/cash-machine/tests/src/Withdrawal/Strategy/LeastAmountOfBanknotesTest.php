<?php
declare(strict_types=1);

namespace BreiteSeite\CashMachineTest\Withdrawal\Strategy;

use BreiteSeite\CashMachine\Currency\BrazilianReal;
use BreiteSeite\CashMachine\Exception\InvalidArgumentException;
use BreiteSeite\CashMachine\Exception\NoteUnavailableException;
use BreiteSeite\CashMachine\Withdrawal\Strategy\LeastAmountOfBanknotes;
use PHPUnit\Framework\TestCase;

class LeastAmountOfBanknotesTest extends TestCase
{
    /**
     * @var LeastAmountOfBanknotes
     */
    private $leastAmountOfBanknotes;

    protected function setUp()
    {
        parent::setUp();

        $this->leastAmountOfBanknotes = new LeastAmountOfBanknotes(new BrazilianReal());
    }

    /**
     * @param float $amount
     * @param array $expectedBankNotes
     * @dataProvider provideValidAmounts
     */
    public function testWithdrawalOfValidAmounts(float $amount = null, array $expectedBankNotes)
    {
        $bankNotes = $this->leastAmountOfBanknotes->withdraw($amount);

        $this->assertEquals($bankNotes, $expectedBankNotes);
    }

    public function provideValidAmounts(): array
    {
        return [
            [30.00, [20.00, 10.00]],
            [80.00, [50.00, 20.00, 10.00]],
            [null, []]
        ];
    }

    public function testWithdrawalOfNegativeAmountsThrowsException()
    {
        $this->expectException(InvalidArgumentException::class);

        $this->leastAmountOfBanknotes->withdraw((float) -130.00);
    }

    public function testWithdrawalWithNullAmountReturnsNull()
    {
        $this->assertSame(
            null,
            $this->leastAmountOfBanknotes->withdraw(null)
        );
    }

    /**
     * @param float $amount
     * @throws NoteUnavailableException
     * @dataProvider provideAmountsUnrepresentableByBanknotesThrowsException
     */
    public function testWithdrawalWithAmountUnrepresentableByBanknotesThrowsException(float $amount)
    {
        $this->expectException(NoteUnavailableException::class);

        $this->leastAmountOfBanknotes->withdraw($amount);
    }

    public function provideAmountsUnrepresentableByBanknotesThrowsException(): array
    {
        return [
          [125.00],
          [20.01],
        ];
    }
}
