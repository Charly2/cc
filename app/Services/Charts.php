<?php

namespace App\Services;

use DB;
use Lava;

class Charts
{
    public static function importacionesExportaciones($empresaId)
    {
        $impExp = DB::table('pedimentos')
            ->select(DB::raw('YEAR(fechaPedimento) as ejercicio,CASE tipoOperacion WHEN 1 THEN count(*) ELSE 0 END as importaciones, CASE tipoOperacion WHEN 2 THEN count(*) ELSE 0 END as exportaciones'))
            ->where('fechaPedimento', '<>', '0000-00-00')
            ->where('empresa_id', $empresaId)
            ->groupBy(DB::raw('YEAR(fechaPedimento),tipoOperacion'))
            ->get();

        $stocksTable = Lava::DataTable();
        $stocksTable->addStringColumn('Ejercicio')
            ->addNumberColumn('Importaciones')
            ->addNumberColumn('Exportaciones');

        foreach ($impExp as $data) {
            $stocksTable->addRow([
              $data->ejercicio, $data->importaciones, $data->exportaciones,
            ]);
        }

        return Lava::BarChart('ImportacionesExportaciones', $stocksTable, [
            'title' => 'Importaciones / Exportaciones',
            'is3D' => true,
            'legend' => ['position' => 'bottom'],
        ]);
    }
}
