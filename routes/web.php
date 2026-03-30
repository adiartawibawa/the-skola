<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// // Route yang memerlukan autentikasi DAN tenant resolution
// Route::middleware(['auth', 'resolve.tenant'])->group(function () {

//     // Route yang butuh permission spesifik
//     Route::middleware(['team.permission:akademik.siswa.view-any'])
//         ->get('/siswa', [\Src\Akademik\Presentation\Http\Controllers\SiswaApiController::class, 'index']);

//     // Atau gunakan shorthand Spatie:
//     Route::middleware(['permission:akademik.nilai.create'])
//         ->post('/nilai', [\Src\Akademik\Presentation\Http\Controllers\NilaiApiController::class, 'store']);
// });
