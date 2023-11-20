<?php

use Illuminate\Support\Facades\Facade;
use Illuminate\Support\ServiceProvider;

return [
	'process_consult' => [
		'minutes_execute' => env('PROCESS_CONSULT_MINUTES_EXECUTE', 5),
	],
	'email_notification' => env('ZOE_EMAIL_NOTIFICATION', 'jovel882@gmail.com')
];
