<?php

namespace App\Entity;

use App\Exceptions\NotFoundCampaignTypeException;

class Device
{
    private const DEVICES = ['desktop', 'mobile'];

    private string $device;

    public function __construct(string $device)
    {
        if (in_array($device, self::DEVICES)) {
            $this->device = $device;
        }
        else {
            throw new NotFoundCampaignTypeException($device);
        }
    }

    /**
     * @return string
     */
    public function getDevice(): string
    {
        return $this->device;
    }

    /**
     * @return array
     */
    public static function getDevices(): array
    {
        return self::DEVICES;
    }

}