<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PagesController;







Route::get('/',[PagesController::class,'root'])->name('root');

// vendor/laravel/ui/src/AuthRouteMethods.php
Auth::routes();

