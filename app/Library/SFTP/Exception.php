<?php
/**
 * Created by PhpStorm.
 * User: Luis Rodríguez
 * Date: 08/08/2018
 * Time: 05:30 PM
 */

namespace App\Library\SFTP;

use App\Exceptions\ExceptionDetailed;


class Exception extends ExceptionDetailed
{
    const ERROR_SEND = 12;
    const ERROR_SEND_OPENLOCAL = Exception::ERROR_SEND << 1;
    const ERROR_SEND_DATA = Exception::ERROR_SEND << 2;

    const ERROR_REVC = 13;
    const ERROR_REVC_OPENREMOTE = Exception::ERROR_REVC << 1;
    const ERROR_REV_DATA = Exception::ERROR_REVC << 2;
}