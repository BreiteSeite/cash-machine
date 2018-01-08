<?php
declare(strict_types=1);

namespace BreiteSeite\CashMachine\Withdrawal\Strategy;

use BreiteSeite\CashMachine\Currency\CurrencyInterface;
use BreiteSeite\CashMachine\Exception\InvalidArgumentException;
use BreiteSeite\CashMachine\Exception\NoteUnavailableException;

final class LeastAmountOfBanknotes implements WithdrawalStrategyInterface
{
    /**
     * @var CurrencyInterface
     */
    private $currency;

    /**
     * LeastAmountOfBankNotes constructor.
     * @param CurrencyInterface $currency
     */
    public function __construct(CurrencyInterface $currency)
    {
        $this->currency = $currency;
    }

    /**
     * @inheritdoc
     */
    public function withdraw(float $amount = null): ?array
    {
        if ($amount === null) {
            return null;
        }

        $bankNoteValuesDescending = $this->getBanknoteValuesDescending();
        $lowestBankNoteValue = $this->getLowestBanknoteValue($bankNoteValuesDescending);

        if (fmod($amount, (float) $lowestBankNoteValue) !== (float)0) {
            throw new NoteUnavailableException('Can not withdraw with current set of banknotes');
        }

        if ($amount < 0) {
            throw new InvalidArgumentException('Can not withdraw negative amounts');
        }

        return $this->getBanknotes($amount, $bankNoteValuesDescending);
    }

    /**
     * @param float[] $bankNoteValues
     * @return float
     */
    private function getLowestBanknoteValue(array $bankNoteValues)
    {
        $lowestBanknoteValue = end($bankNoteValues);
        reset($bankNoteValues);

        return $lowestBanknoteValue;
    }

    /**
     * @return array|float[]
     */
    private function getBanknoteValuesDescending()
    {
        $bankNoteValues = $this->currency->getAvailableBankNoteValues();
        if (false === rsort($bankNoteValues)) {
            throw new \RuntimeException('Could not sort banknote values');
        }
        return $bankNoteValues;
    }

    /**
     * @param float $amount
     * @param float[] $bankNoteValuesDescending
     * @return float[]
     */
    private function getBanknotes(float $amount, array $bankNoteValuesDescending): array
    {
        $pendingWithdrawal = $amount;
        $bankNotes = [];
        foreach ($bankNoteValuesDescending as $bankNoteValue) {
            while ($pendingWithdrawal >= $bankNoteValue) {
                $bankNotes[] = $bankNoteValue;

                $pendingWithdrawal -= $bankNoteValue;
            }
        }

        return $bankNotes;
    }
}
