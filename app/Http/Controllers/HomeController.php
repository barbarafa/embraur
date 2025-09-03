<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Cursos;

class HomeController extends Controller
{
    public function index()
    {
        $populares = Cursos::where('status', 'publicado')->get();
        return view('site.home', compact('populares'));
    }
}

