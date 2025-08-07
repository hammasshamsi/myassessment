<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Onboarding\ResumeController;
use App\Http\Controllers\Onboarding\Step1Controller;
use App\Http\Controllers\Onboarding\Step2Controller;
use App\Http\Controllers\Onboarding\Step3Controller;
use App\Http\Controllers\Onboarding\Step4Controller;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/getstarted', function () {
    return view('getStarted');
})->name('getstarted');

Route::get('/onboarding/start-new', function () {
    session()->forget('onboarding_token');
    return redirect()->route('onboarding.step1');
})->name('onboarding.new');

Route::prefix('onboarding')->group(function () {
    Route::get('resume', ResumeController::class)->name('onboarding.resume');

    Route::get('/step-1', [Step1Controller::class, 'show'])->name('onboarding.step1');
    Route::post('/step-1', [Step1Controller::class, 'store']);

    Route::get('/step-2', [Step2Controller::class, 'show'])->name('onboarding.step2')->middleware('signed');
    Route::post('/step-2', [Step2Controller::class, 'store']);

    Route::get('/step-3', [Step3Controller::class, 'show'])->name('onboarding.step3')->middleware('signed');
    Route::post('/step-3', [Step3Controller::class, 'store']);

    Route::get('/step-4', [Step4Controller::class, 'show'])->name('onboarding.step4')->middleware('signed');
    Route::post('/step-4', [Step4Controller::class, 'store']);

    Route::get('/step-5', [Step5Controller::class, 'show'])->name('onboarding.step5')->middleware('signed');
    Route::post('/step-5', [Step5Controller::class, 'store']);
});
