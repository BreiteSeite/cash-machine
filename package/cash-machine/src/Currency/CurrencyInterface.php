<?php
declare(strict_types=1);

namespace BreiteSeite\CashMachine\Currency;

interface CurrencyInterface
{
    /**
     * @return float[]
     */
    public function getAvailableBankNoteValues(): array;
}
