<?php
declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

$silexApp = new \Silex\Application(['debug' => true]);

$silexApp->get('/withdraw/{providedAmount}', function (string $providedAmount) use ($silexApp) {
    $amountIsNull = ($providedAmount === 'null');
    if ($amountIsNull) {
        $amount = null;
    } else {
        $amountInFloat = filter_var($providedAmount, FILTER_VALIDATE_FLOAT);

        if ($amountInFloat === false) {
            throw new \RuntimeException('Withdrawal amount is not a float value');
        }

        $amount = $amountInFloat;
    }


    $withdrawal = new \BreiteSeite\CashMachine\Withdrawal\Strategy\LeastAmountOfBanknotes(
        new \BreiteSeite\CashMachine\Currency\BrazilianReal()
    );

    return json_encode($withdrawal->withdraw($amount), JSON_PRETTY_PRINT);
});

$silexApp->run();
