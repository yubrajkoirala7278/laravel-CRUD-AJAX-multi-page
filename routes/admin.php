<?php

use App\Http\Controllers\Admin\BlogController;
use Illuminate\Support\Facades\Route;
// for multiple page
Route::get('/blogs',[BlogController::class,'home'])->name('blogs');
Route::get('/blogs/index', [BlogController::class, 'index'])->name('blogs.index');
Route::get('/blogs/create',[BlogController::class,'create'])->name('blogs.create');
Route::post('/blogs/store', [BlogController::class, 'store'])->name('blogs.store');
Route::get('/blogs/{slug}',[BlogController::class,'show'])->name('blogs.show');
Route::delete('/blogs/destroy/{slug}', [BlogController::class, 'destroy'])->name('blogs.destroy');
Route::get('/blogs/{slug}', [BlogController::class, 'show'])->name('blogs.show');
Route::get('/blogs/{slug}/edit', [BlogController::class, 'edit'])->name('blogs.edit');
Route::put('/blogs/update/{blog}', [BlogController::class, 'update'])->name('blogs.update');