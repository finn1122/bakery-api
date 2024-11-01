<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/api'); // Redirige a la raíz de la API
});
