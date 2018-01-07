<?php
declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

$silexApp = new \Silex\Application(['debug' => true]);


$silexApp->get('/withdraw/{amount}', function (string $amount) use ($silexApp) {
    $amount = filter_var($amount, FILTER_VALIDATE_FLOAT);

    if ($amount === false) {
        throw new \RuntimeException('Withdrawal amount is not a float value');
    }

    $withdrawal = new \BreiteSeite\CashMachine\Withdrawal\Strategy\LeastAmountOfBanknotes(
        new \BreiteSeite\CashMachine\Currency\BrazilianReal()
    );

    return json_encode($withdrawal->withdraw($amount), JSON_PRETTY_PRINT);
});

$silexApp->run();
