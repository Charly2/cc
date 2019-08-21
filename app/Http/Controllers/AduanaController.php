<?php

namespace App\Http\Controllers;

use App\Repositories\EloquentAduanaRepository;
use Illuminate\Routing\Controller as BaseController;

class AduanaController extends BaseController
{
    protected $aduana;

    public function __construct(EloquentAduanaRepository $aduana)
    {
        $this->aduana = $aduana;
    }
}