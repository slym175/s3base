<?php

use Illuminate\Support\Facades\Route;
use S3base\Core\Http\Controllers\DebugController;
use S3base\Core\Http\Controllers\LanguageController;

Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::get('languages/{language}', [LanguageController::class, 'setLanguage'])
        ->name('languages.update');

    Route::group(['prefix' => '_debug', 'as' => '_debug.'], function () {
        Route::get('cache-clear', [DebugController::class, 'cacheClear'])
            ->name('cache-clear');

        Route::get('optimize-clear', [DebugController::class, 'optimizeClear'])
            ->name('optimize-clear');
    });
});
