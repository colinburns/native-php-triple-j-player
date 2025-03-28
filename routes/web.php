<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RadioController;

// Main route for our radio player
Route::get('/', function () {
    return view('radio');
});

// Add the now-playing endpoint directly to web routes
Route::get('/now-playing', [RadioController::class, 'nowPlaying']);

// Keep the original example route
Route::get('/openInBrowser', function () {
    nativephp_openInBrowser('https://nativecli.com/');
    dd('here');
    //return redirect()->back();
})->name('openInBrowser');
