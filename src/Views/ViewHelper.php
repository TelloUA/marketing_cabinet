<?php

namespace App\Views;

class ViewHelper
{
    public static function drawOptions(array $array, string $option): string
    {
        $allRows = '';
        foreach ($array as $item) {
            $row = '<option value="'.$item.'"';
            if ($option == $item) {
                $row .= ' selected';
            }
            $row .= '>'.$item.'</option>';
            $allRows .= $row;
        }
        return $allRows;
    }
}