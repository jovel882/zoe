<?php

namespace App\Services\Mail;

use Throwable;

class SendAlertErrorSynPrice extends SendEmail
{
    private Throwable $throw;
    private string $securityTypeName;

    public function __invoke(Throwable $throw, string $securityTypeName)
    {
        $this->setThrow($throw)
            ->setSecurityTypeName($securityTypeName)
            ->send();
    }

    public function getThrow(): Throwable
    {
        return $this->throw;
    }

    public function setThrow(Throwable $throw): self
    {
        $this->throw = $throw;
        return $this;
    }

    public function getSecurityTypeName(): string
    {
        return $this->securityTypeName;
    }

    public function setSecurityTypeName(string $securityTypeName): self
    {
        $this->securityTypeName = $securityTypeName;
        return $this;
    }

    public function getBody(): string
    {
        $space = "\n----------------------------------------------------------------\n";
        return  __(
            'An error was generated when synchronizing prices for the type :securityType, these are the details:',
            ['securityType' => $this->securityTypeName]
        )
        . $space
        . __(
            'Message: :data',
            ['data' => $this->throw->getMessage()]
        )
        . $space
        . __(
            'File: :data',
            ['data' => $this->throw->getFile()]
        )
        . $space
        . __(
            'Line: :data',
            ['data' => $this->throw->getLine()]
        )
        . $space;
    }

    public function getSubject(): string
    {
        return __('Synchronized price failed notification.');
    }
}
