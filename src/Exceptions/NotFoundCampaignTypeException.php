<?php

namespace App\Exceptions;

use RuntimeException;

class NotFoundCampaignTypeException extends RuntimeException
{
    public function __construct(string $type)
    {
        parent::__construct("Not found campaign with type - ". $type);
    }
}