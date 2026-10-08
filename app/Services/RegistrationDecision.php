<?php

namespace App\Services;

use App\Models\EventRegistration;

class RegistrationDecision
{
    public function __construct(
        public EventRegistration $registration,
        public bool $changed,
    ) {}
}
