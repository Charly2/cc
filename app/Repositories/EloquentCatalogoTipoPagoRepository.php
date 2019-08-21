<?php
/**
 * Created by PhpStorm.
 * User: cesar
 * Date: 27/04/16
 * Time: 08:36 AM.
 */
namespace App\Repositories;

use App\CatalogoTipoPago;
use Illuminate\Support\Facades\Hash;

class EloquentCatalogoTipoPagoRepository
{
    /**
     * @return Collection
     */
    public function all()
    {
        return CatalogoTipoPago::all();
    }

}
