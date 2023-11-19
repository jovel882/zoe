<?php

namespace App\Services\Mail;

class SendEmailSyncPrice extends SendEmail
{
    private array $securityPrices;

    public function __invoke(array $securityPrices)
    {
        $this->setSecurityPrices($securityPrices)
            ->send();
    }

    private function getAllSecurityPricesToBodyMessage(array $securityPrices)
    {
        return array_reduce(
            $securityPrices,
            fn($body, $price) => $body
                . __('ID: :data', ['data' => $price['id']]) . "\n"
                . __('Security Symbol: :data', ['data' => $price['security']]) . "\n"
                . __('Price: :data', ['data' => $price['last_price']]) . "\n"
                . __('Date Time: :data', ['data' => $price['as_of_date']]) . "\n"
                . "----------------------------------------------------------------\n"
        );
    }

    public function getSecurityPrices(): array
    {
        return $this->securityPrices;
    }

    public function setSecurityPrices(array $securityPrices): self
    {
        $this->securityPrices = $securityPrices;
        return $this;
    }

    public function getBody(): string
    {
        return  __(
            'For type :securityType the following prices have been updated:',
            ['securityType' => $this->securityPrices['securityType']]
        )
        . "\n\n----------------------------------------------------------------\n"
        . $this->getAllSecurityPricesToBodyMessage($this->securityPrices['securityPrices']);
    }

    public function getSubject(): string
    {
        return __('Synchronized price notification.');
    }
}
