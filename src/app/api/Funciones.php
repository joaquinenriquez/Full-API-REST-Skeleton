<?php

function replace_null($value, $replace) {
    if (!isset($value)) {
        return $replace;
    } else {
        return $value;
    }
}

?>