<?php

namespace App\Http\Controllers;

use App\Models\Curso;

class HomeController extends Controller
{
    public function index()
    {
        $populares = Curso::where('popular', true)->take(4)->get();
        return view('site.home', compact('populares'));
    }
}

