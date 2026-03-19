<?php

use Illuminate\Support\Facades\Route;
use hexa_package_google_speech\Http\Controllers\GoogleSpeechSettingController;

Route::middleware(['web', 'auth', 'locked', 'system_lock', 'two_factor', 'role'])->group(function () {

    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/google-speech', [GoogleSpeechSettingController::class, 'index'])->name('google-speech');
        Route::post('/google-speech', [GoogleSpeechSettingController::class, 'save'])->name('google-speech.save');
    });

});
