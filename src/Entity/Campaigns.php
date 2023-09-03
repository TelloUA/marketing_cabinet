<?php

namespace App\Entity;

class Campaigns
{
    private string $name;

    private CampaignType $type;

    private Device $device;

    private int $geo;

    private string $url;

    private string $date;

    public function __construct()
    {

    }


}