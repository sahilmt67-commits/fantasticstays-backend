<?php

use App\Http\Controllers\Api\AmenityApiController;
use App\Http\Controllers\Api\BookingApiController;
use App\Http\Controllers\Api\EnquiryApiController;
use App\Http\Controllers\Api\HomepageSectionApiController;
use App\Http\Controllers\Api\FaqApiController;
use App\Http\Controllers\Api\LocationApiController;
use App\Http\Controllers\Api\OccasionApiController;
use App\Http\Controllers\Api\ReviewApiController;
use App\Http\Controllers\Api\ServiceLeadApiController;
use App\Http\Controllers\Api\SettingApiController;
use App\Http\Controllers\Api\VillaApiController;
use App\Http\Controllers\Api\WhyChooseApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// API Version 1 Routes
Route::prefix('v1')->group(function () {
    // Villas
    Route::get('/villas', [VillaApiController::class, 'index']);
    Route::get('/featured-villas', [VillaApiController::class, 'featured']);
    Route::get('/filter-options', [VillaApiController::class, 'filterOptions']);
    Route::get('/villas/{slug}', [VillaApiController::class, 'show']);

    // Bookings & Availability
    Route::post('/bookings', [BookingApiController::class, 'store']);
    Route::post('/bookings/check-availability', [BookingApiController::class, 'checkAvailability']);

    // Enquiries & WhatsApp Leads
    Route::post('/enquiries', [EnquiryApiController::class, 'store']);
    Route::post('/service-leads', [ServiceLeadApiController::class, 'store']);

    // Taxonomy & Content
    Route::get('/locations', [LocationApiController::class, 'index']);
    Route::get('/occasions', [OccasionApiController::class, 'index']);
    Route::get('/why-choose', [WhyChooseApiController::class, 'index']);
    Route::get('/amenities', [AmenityApiController::class, 'index']);
    Route::get('/reviews', [ReviewApiController::class, 'index']);
    Route::post('/reviews', [ReviewApiController::class, 'store']);
    Route::get('/faqs', [FaqApiController::class, 'index']);
    Route::get('/settings', [SettingApiController::class, 'index']);
    Route::get('/homepage-sections', [HomepageSectionApiController::class, 'index']);
});

// Direct fallback aliases
Route::get('/villas', [VillaApiController::class, 'index']);
Route::get('/featured-villas', [VillaApiController::class, 'featured']);
Route::get('/filter-options', [VillaApiController::class, 'filterOptions']);
Route::get('/villas/{slug}', [VillaApiController::class, 'show']);
Route::post('/bookings', [BookingApiController::class, 'store']);
Route::post('/enquiries', [EnquiryApiController::class, 'store']);
Route::get('/locations', [LocationApiController::class, 'index']);
Route::get('/occasions', [OccasionApiController::class, 'index']);
Route::get('/why-choose', [WhyChooseApiController::class, 'index']);
Route::get('/amenities', [AmenityApiController::class, 'index']);
Route::get('/reviews', [ReviewApiController::class, 'index']);
Route::get('/faqs', [FaqApiController::class, 'index']);
Route::get('/settings', [SettingApiController::class, 'index']);
Route::get('/homepage-sections', [HomepageSectionApiController::class, 'index']);
