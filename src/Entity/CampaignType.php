<?php

namespace App\Entity;

use App\Exceptions\NotFoundCampaignTypeException;

class CampaignType
{
    private static array $TYPES = ['product', 'push'];

    private string $type;

    public function __construct(string $type)
    {
        if (in_array($type, self::$TYPES)) {
            $this->type = $type;
        }
        else {
            throw new NotFoundCampaignTypeException($type);
        }
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return array
     */
    public static function getTypes(): array
    {
        return self::$TYPES;
    }

}