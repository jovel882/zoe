<?php

namespace App\Services\Mail;

use Illuminate\Support\Facades\Mail;

abstract class SendEmail
{
    public function send()
    {
        Mail::raw(
            $this->getBody(),
            fn ($message) =>
                $message->to($this->getTo())
                    ->subject($this->getSubject())
        );
    }

    public function getTo(): string
    {
        return config('zoe.email_notification');
    }

    abstract public function getBody(): string;

    abstract public function getSubject(): string;
}
