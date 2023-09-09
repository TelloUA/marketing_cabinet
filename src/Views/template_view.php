<?php


// TEMPORARY NAME, need to remove function.php !!!
function drawSelectOptions2($array, $option): string
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

include 'src/blocks/header.php';
include $content_view;
include 'src/blocks/footer.php';