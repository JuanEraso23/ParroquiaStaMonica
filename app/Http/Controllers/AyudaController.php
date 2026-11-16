<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

/**
 * Controlador de la sección de Ayuda.
 *
 * Muestra el centro de ayuda con guías para feligreses,
 * secretaría, párroco y vicario.
 */
class AyudaController extends Controller
{
    /**
     * Muestra el índice del centro de ayuda.
     */
    public function index(): View
    {
        return view('ayuda.index');
    }
}