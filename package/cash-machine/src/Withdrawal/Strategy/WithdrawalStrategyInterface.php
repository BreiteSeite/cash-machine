<?php
declare(strict_types=1);

namespace BreiteSeite\CashMachine\Withdrawal\Strategy;

use BreiteSeite\CashMachine\Exception\InvalidArgumentException;
use BreiteSeite\CashMachine\Exception\NoteUnavailableException;

interface WithdrawalStrategyInterface
{
    /**
     * @param float|null $amount
     * @return float[]|null
     * @throws NoteUnavailableException if you try to withdraw an amount that can not be represented by banknotes
     * @throws InvalidArgumentException if you try to withdraw a negative amount
     */
    public function withdraw(float $amount): ?array;
}
