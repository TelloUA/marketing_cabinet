<?php

namespace App\Entity;

class CommunicationChannelType
{
    private static array $COMMUNICATION_CHANNELS = ['telegram', 'email', 'skype'];

    private string $type;

    public static function getCommunicationChannels(): array
    {
        return self::$COMMUNICATION_CHANNELS;
    }
}