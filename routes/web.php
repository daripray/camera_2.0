<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

use App\Livewire\FrontOffice;
use App\Livewire\BackOffice;

Route::get('/fo', FrontOffice::class)->name('fo');
Route::get('/bo', BackOffice::class)->name('bo');

Route::post('/upload', function () {
    $file = request()->file('video');
    $filename = time() . '_' . $file->getClientOriginalName();
    $file->storeAs('public/videos', $filename);

    \App\Helpers\Logger::write("Video uploaded: " . $filename);
    return response('OK');
})->name('upload-video');


use App\Livewire\LogViewer;
Route::get('/log', LogViewer::class);

