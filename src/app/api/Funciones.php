<?php

function replace_null($value, $replace) {
    if (!isset($value)) {
        return $replace;
    } else {
        return $value;
    }
}

function GenerarCodigoAmigable() {
    
    $caracteres = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $cantidadCaracteres = strlen($caracteres) - 1; 

    // Buscamos un numero aleatorio de entre 0 y la cantidad de caracteres
    // Ese numero lo utilizamos como comienzo del substring de largo 1
    $caracter1 = substr($caracteres, rand(0, $cantidadCaracteres), 1);
    $caracter2 = substr($caracteres, rand(0, $cantidadCaracteres), 1);
    $caracter3 = substr($caracteres, rand(0, $cantidadCaracteres), 1);
    $caracter4 = substr($caracteres, rand(0, $cantidadCaracteres), 1);
    $caracter5 = substr($caracteres, rand(0, $cantidadCaracteres), 1);

    $codigo = $caracter1 . $caracter2 . $caracter3 . $caracter4 . $caracter5;

    return $codigo;

}

function SumarTiempos ($times) {
    $minutes = 0; //declare minutes either it gives Notice: Undefined variable
    // loop throught all the times
    foreach ($times as $time) {
        list($hour, $minute) = explode(':', $time);
        $minutes += $hour * 60;
        $minutes += $minute;
    }

    $hours = floor($minutes / 60);
    $minutes -= $hours * 60;

    // returns the time already formatted
    return sprintf('%02d:%02d', $hours, $minutes);
}

?>