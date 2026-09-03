<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminEventController;
use App\Http\Controllers\Admin\CommitteeController;
use App\Http\Controllers\Admin\TitleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\EventController;
use App\Http\Middleware\EnsureUserIsSuperAdmin;
use App\Http\Middleware\EnsurePinIsConfigured;
use App\Models\Committee;
use Illuminate\Support\Facades\Route;

// Landing / Login Page (with direct redirect check to prevent double authentications)
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }

    return view('login');
})->name('login');

// Google Authentication Endpoints
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('auth.google.callback');

// Public Shareable Event Landing & RSVP Routes (Guest Accessible)
Route::get('/events/{event}', [EventController::class, 'showPublic'])->name('events.public_show');
Route::post('/events/{event}/register', [EventController::class, 'registerPublic'])->name('events.public_register');
Route::post('/events/{event}/request-access', [EventController::class, 'requestAccessLink'])->name('events.request_access');

// Secure Signed Tickets & Luma-Style Cancellation Gateway
Route::get('/rsvp/{registration}/manage', [EventController::class, 'showTicket'])->name('events.manage_ticket');
Route::post('/rsvp/{registration}/cancel', [EventController::class, 'cancelRegistration'])->name('events.cancel_registration');

// Mobile Self Check-In Portal
Route::get('/events/{event}/check-in', [EventController::class, 'showCheckIn'])->name('events.check_in');
Route::post('/events/{event}/check-in', [EventController::class, 'submitCheckIn'])->name('events.submit_check_in');
Route::get('/events/{event}/check-in/success', [EventController::class, 'checkInSuccess'])->name('events.check_in_success');

// Authenticated Sessions Group
Route::middleware(['auth'])->group(function () {
    // Session Logout
    Route::post('/logout', [GoogleController::class, 'logout'])->name('logout');

    // First-Time Access PIN Configuration
    Route::get('/setup-pin', [GoogleController::class, 'showSetupPin'])->name('pin.setup');
    Route::post('/setup-pin', [GoogleController::class, 'saveSetupPin'])->name('pin.save');

    // returning users PIN 2FA Verification
    Route::get('/verify-pin', [GoogleController::class, 'showVerifyPin'])->name('pin.verify');
    Route::post('/verify-pin', [GoogleController::class, 'submitVerifyPin'])->name('pin.verify.submit');

    // Secure App Portal (PIN Configuration Required)
    Route::middleware([EnsurePinIsConfigured::class])->group(function () {
        // Central Divison Switcher
        Route::get('/dashboard', function () {
            $committees = Committee::with(['titles.users'])->latest()->get();

            return view('dashboard', compact('committees'));
        })->name('dashboard');

        // Unified Committee Events Application Portal
        Route::get('/committees/events', [EventController::class, 'index'])->name('committees.events.index');

        // Dedicated Event Management Page
        Route::get('/committees/events/{event}', [EventController::class, 'manage'])->name('committees.events.manage');

        // Dynamic Committee Events Application Portal
        Route::get('/committees/{committee}/events', [EventController::class, 'committeeEvents'])->name('committees.events');

        // Committee Event Management Actions (Available to anyone in the committee)
        Route::post('/committees/events', [AdminEventController::class, 'store'])->name('committees.events.store');
        Route::put('/committees/events/{event}', [AdminEventController::class, 'update'])->name('committees.events.update');
        Route::delete('/committees/events/{event}', [AdminEventController::class, 'destroy'])->name('committees.events.destroy');
        Route::post('/committees/registrations/{registration}/approve', [AdminEventController::class, 'approveRegistration'])->name('committees.registrations.approve');
        Route::post('/committees/registrations/{registration}/decline', [AdminEventController::class, 'declineRegistration'])->name('committees.registrations.decline');
        Route::post('/committees/registrations/{registration}/toggle-attendance', [AdminEventController::class, 'toggleAttendance'])->name('committees.registrations.toggle_attendance');
        Route::delete('/committees/registrations/{registration}', [AdminEventController::class, 'destroyRegistration'])->name('committees.registrations.destroy');
        Route::post('/committees/registrations/bulk-approve', [AdminEventController::class, 'bulkApproveRegistrations'])->name('committees.registrations.bulk_approve');
        Route::post('/committees/registrations/bulk-decline', [AdminEventController::class, 'bulkDeclineRegistrations'])->name('committees.registrations.bulk_decline');
        Route::post('/committees/registrations/bulk-delete', [AdminEventController::class, 'bulkDestroyRegistrations'])->name('committees.registrations.bulk_delete');

        // Whitelisted Super Admin Operations
        Route::middleware([EnsureUserIsSuperAdmin::class])->prefix('admin')->name('admin.')->group(function () {
            // Consolidated Cockpit
            Route::get('/', [AdminController::class, 'index'])->name('index');

            // User Access Control Actions
            Route::post('/users', [UserController::class, 'store'])->name('users.store');
            Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
            Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
            Route::post('/users/{user}/reset-pin', [UserController::class, 'resetPin'])->name('users.reset_pin');

            // Professional Title Configurations Actions
            Route::post('/titles', [TitleController::class, 'store'])->name('titles.store');
            Route::put('/titles/{title}', [TitleController::class, 'update'])->name('titles.update');
            Route::delete('/titles/{title}', [TitleController::class, 'destroy'])->name('titles.destroy');

            // Dynamic Committee Configurations
            Route::post('/committees', [CommitteeController::class, 'store'])->name('committees.store');
            Route::put('/committees/{committee}', [CommitteeController::class, 'update'])->name('committees.update');
            Route::delete('/committees/{committee}', [CommitteeController::class, 'destroy'])->name('committees.destroy');
        });
    });
});
