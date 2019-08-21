<?php
/**
 * Created by PhpStorm.
 * User: rprieto
 * Date: 24/07/2018
 * Time: 03:54 PM
 */

namespace App\Http\Controllers;

class CargasController extends Controller
{

    /*
     * @var conexion sftp
     */
    protected $sftp;

    public function __construct(){

    }

    public function show(){



//        $this->sftp = new SFtp($row->host, 22);
//        echo "se realiza la conexion";
//        $this->sftp->connect($row->user, $row->password);
        return view('cargas.cargas');
    }
}