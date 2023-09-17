<?php

namespace App\Core;

class InputTransformer
{
    public static function transform(?string $value, $isEmail = false): ?string
    {
        if ($value === null) {
            return null;
        } else {
            $value = trim($value);
            $value = stripslashes($value);
            $value = htmlspecialchars($value);
            if ($isEmail) {
                $value = strtolower($value);
            }
        }
        return $value;
    }

}