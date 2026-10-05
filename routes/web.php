<?php

use App\Http\Controllers\Admin\AmenityController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EnquiryController;
use App\Http\Controllers\Admin\HomepageContentController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\Admin\OccasionController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\ServiceLeadController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\VillaController;
use App\Http\Controllers\Admin\WhyChooseController;
use Illuminate\Support\Facades\Route;

// Redirect root to Admin Dashboard
Route::get('/', function () {
    return redirect()->route('admin.dashboard');
});

// Admin Auth Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Protected Admin Routes
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // Villas
        Route::resource('villas', VillaController::class);
        Route::post('villas/{villa}/featured', [VillaController::class, 'toggleFeatured'])->name('villas.featured');
        Route::get('villas/{villa}/rooms', [VillaController::class, 'rooms'])->name('villas.rooms');
        Route::post('villas/{villa}/rooms', [VillaController::class, 'storeRoom'])->name('villas.rooms.store');
        Route::put('rooms/{room}', [VillaController::class, 'updateRoom'])->name('rooms.update');
        Route::delete('rooms/{room}', [VillaController::class, 'deleteRoom'])->name('rooms.destroy');
        // Image upload (AJAX)
        Route::post('villas/upload-image', [VillaController::class, 'uploadImage'])->name('villas.upload-image');

        // Bookings
        Route::get('bookings', [BookingController::class, 'index'])->name('bookings.index');
        Route::get('bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
        Route::put('bookings/{booking}/status', [BookingController::class, 'updateStatus'])->name('bookings.status');
        Route::delete('bookings/{booking}', [BookingController::class, 'destroy'])->name('bookings.destroy');

        // Enquiries & Leads
        Route::get('enquiries', [EnquiryController::class, 'index'])->name('enquiries.index');
        Route::put('enquiries/{enquiry}/status', [EnquiryController::class, 'updateStatus'])->name('enquiries.status');
        Route::delete('enquiries/{enquiry}', [EnquiryController::class, 'destroy'])->name('enquiries.destroy');

        Route::get('service-leads', [ServiceLeadController::class, 'index'])->name('service-leads.index');
        Route::put('service-leads/{serviceLead}/status', [ServiceLeadController::class, 'updateStatus'])->name('service-leads.status');
        Route::delete('service-leads/{serviceLead}', [ServiceLeadController::class, 'destroy'])->name('service-leads.destroy');

        // Reviews Moderation
        Route::get('reviews', [ReviewController::class, 'index'])->name('reviews.index');
        Route::put('reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
        Route::post('reviews/{review}/approval', [ReviewController::class, 'toggleApproval'])->name('reviews.approval');
        Route::post('reviews/{review}/featured', [ReviewController::class, 'toggleFeatured'])->name('reviews.featured');
        Route::delete('reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

        // Locations
        Route::post('locations/upload-image', [LocationController::class, 'uploadImage'])->name('locations.upload-image');
        Route::get('locations', [LocationController::class, 'index'])->name('locations.index');
        Route::post('locations', [LocationController::class, 'store'])->name('locations.store');
        Route::put('locations/{location}', [LocationController::class, 'update'])->name('locations.update');
        Route::delete('locations/{location}', [LocationController::class, 'destroy'])->name('locations.destroy');

        // Occasions
        Route::post('occasions/upload-image', [OccasionController::class, 'uploadImage'])->name('occasions.upload-image');
        Route::get('occasions', [OccasionController::class, 'index'])->name('occasions.index');
        Route::post('occasions', [OccasionController::class, 'store'])->name('occasions.store');
        Route::put('occasions/{occasion}', [OccasionController::class, 'update'])->name('occasions.update');
        Route::delete('occasions/{occasion}', [OccasionController::class, 'destroy'])->name('occasions.destroy');

        // Why Choose homepage points
        Route::get('why-choose', [WhyChooseController::class, 'index'])->name('why-choose.index');
        Route::post('why-choose', [WhyChooseController::class, 'store'])->name('why-choose.store');
        Route::put('why-choose/{whyChoose}', [WhyChooseController::class, 'update'])->name('why-choose.update');
        Route::delete('why-choose/{whyChoose}', [WhyChooseController::class, 'destroy'])->name('why-choose.destroy');

        // Amenities
        Route::get('amenities', [AmenityController::class, 'index'])->name('amenities.index');
        Route::post('amenities', [AmenityController::class, 'store'])->name('amenities.store');
        Route::put('amenities/{amenity}', [AmenityController::class, 'update'])->name('amenities.update');
        Route::delete('amenities/{amenity}', [AmenityController::class, 'destroy'])->name('amenities.destroy');

        // Settings
        Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
        Route::post('settings', [SettingController::class, 'update'])->name('settings.update');
        Route::post('settings/sections', [HomepageContentController::class, 'updateCopy'])->name('settings.sections');
        Route::post('settings/upload-image', [HomepageContentController::class, 'uploadImage'])->name('settings.upload-image');
        Route::post('settings/experiences', [HomepageContentController::class, 'storeExperience'])->name('settings.experiences.store');
        Route::put('settings/experiences/{experience}', [HomepageContentController::class, 'updateExperience'])->name('settings.experiences.update');
        Route::delete('settings/experiences/{experience}', [HomepageContentController::class, 'destroyExperience'])->name('settings.experiences.destroy');
        Route::post('settings/stats', [HomepageContentController::class, 'storeStat'])->name('settings.stats.store');
        Route::put('settings/stats/{stat}', [HomepageContentController::class, 'updateStat'])->name('settings.stats.update');
        Route::delete('settings/stats/{stat}', [HomepageContentController::class, 'destroyStat'])->name('settings.stats.destroy');
        Route::post('settings/posts', [HomepageContentController::class, 'storePost'])->name('settings.posts.store');
        Route::put('settings/posts/{post}', [HomepageContentController::class, 'updatePost'])->name('settings.posts.update');
        Route::delete('settings/posts/{post}', [HomepageContentController::class, 'destroyPost'])->name('settings.posts.destroy');
        Route::post('settings/faqs', [HomepageContentController::class, 'storeFaq'])->name('settings.faqs.store');
        Route::put('settings/faqs/{homepageFaq}', [HomepageContentController::class, 'updateFaq'])->name('settings.faqs.update');
        Route::delete('settings/faqs/{homepageFaq}', [HomepageContentController::class, 'destroyFaq'])->name('settings.faqs.destroy');
    });
});
