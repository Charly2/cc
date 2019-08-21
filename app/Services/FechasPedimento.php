<?php

namespace App\Services;

class FechasPedimento
{
    protected static $fechas = [
        '1' => 'Entrada',
        '2' => 'Pago',
        '3' => 'Extraccion',
        '5' => 'Presentacion',
        '6' => 'IMP. EUA/CAN F',
        '7' => 'Original',
    ];

    public static function getDescripcion($tipoFecha = '')
    {
        return isset(static::$fechas[$tipoFecha])
        ? static::$fechas[$tipoFecha]
        : 'No definido';
    }
}
