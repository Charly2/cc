<?php

namespace App\Services;

use GuzzleHttp\Client;

/**
* 
*/
class MandarPagos
{
	protected $client;
    protected $pedimento;
    protected $baseUrl = 'http://internal-cpabase-2031371593.us-east-1.elb.amazonaws.com:8081/ctrade/';
    protected $defaults;
	
	/*function __construct(argument)
	{
		# code...
	}*/
}