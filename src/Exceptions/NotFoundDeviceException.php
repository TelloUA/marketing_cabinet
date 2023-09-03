<?php

namespace App\Exceptions;

use RuntimeException;

class NotFoundDeviceException extends RuntimeException
{
    public function __construct(string $device)
    {
        parent::__construct("Not found device - ". $device);
    }
}