<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Promocao;
use App\Models\Servico;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $promocoes = Servico::where('ativo',true)->where('promocao',true)->get();
        $banners = Banner::whereNotNull('foto')->where('ativo',true)->orderBy('ordem')->get();
        return view('cliente.home',compact('promocoes','banners'));
    }
}
