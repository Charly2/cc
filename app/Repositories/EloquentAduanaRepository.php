<?php

namespace App\Repositories;

use App\Aduana;

class EloquentAduanaRepository
{
    /**
     * @return mixed
     */
    public function getAll()
    {
        return Aduana::all();
    }
}
