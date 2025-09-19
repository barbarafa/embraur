<?php

namespace App\Http\Controllers;

use App\Models\Cursos;


class HomeController extends Controller
{
    public function index()
    {
        $populares = Cursos::where('status', 'publicado')->get();


        $parceiros = collect([
            ['logo' => 'logo-mmx3.png',         'alt' => 'MMX3',       'url' => null],
            ['logo' => 'logo-criservice.png',   'alt' => 'CriService', 'url' => null],
            ['logo' => 'logo-total-plast.png',  'alt' => 'Totalplast', 'url' => null],
            ['logo' => 'LOGO-ISOBRAS.png',      'alt' => 'ISOBRAS',    'url' => null],
            // pode adicionar mais
        ]);

        return view('site.home', compact('populares','parceiros'));
    }
}

