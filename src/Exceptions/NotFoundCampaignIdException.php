<?php

namespace App\Exceptions;

use RuntimeException;

class NotFoundCampaignIdException extends RuntimeException
{
    public function __construct(string $id)
    {
        parent::__construct("Client does not have a campaign with ID - ". $id);
    }
}