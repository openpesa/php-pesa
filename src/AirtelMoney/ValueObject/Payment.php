<?php
declare(strict_types=1);

namespace Openpesa\Sdk\AirtelMoney\ValueObject;

use Webmozart\Assert\Assert;

class Payment
{
    private string $reference;
    private Subscriber $subscriber;
    private Transaction $transaction;

    public function __construct(
        string $reference,
        float $amount,
        string $msisdn,
        string $country = 'CD',
        string $currency = 'CDF'
    ) {
        Assert::stringNotEmpty($reference);
        $this->reference = $reference;

        $this->subscriber = new Subscriber([
            'country' => $country,
            'currency' => $currency,
            'msisdn' => $msisdn
        ]);

        $this->transaction = new Transaction([
            'amount' => $amount,
            'country' => $country,
            'current' => $currency,
            'id' => uniqid('airtel_transaction_')
        ]);
    }

    public function getReference(): string
    {
        return $this->reference;
    }

    public function getSubscriber(): Subscriber
    {
        return $this->subscriber;
    }

    public function getTransaction(): Transaction
    {
        return $this->transaction;
    }
}
