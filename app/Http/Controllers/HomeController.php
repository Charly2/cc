<?php

namespace App\Http\Controllers;

use App\Pedimento;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    protected $pedimento;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Pedimento $pedimento)
    {
        $this->pedimento = $pedimento;
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }
}
