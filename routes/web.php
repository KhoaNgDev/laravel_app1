<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;



Route::get('/', function () {
    return view('admin.auth.login');
})->name('welcome');


require __DIR__.'/auth.php';
