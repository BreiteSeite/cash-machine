<?php
declare(strict_types=1);

namespace BreiteSeite\CashMachine\Currency;

final class BrazilianReal implements CurrencyInterface
{
    /**
     * @return float[] returns a list of available banknote values
     */
    public function getAvailableBankNoteValues(): array
    {
        return [100.00, 50.00, 20.00, 10.00];
    }
}
