<?php


use App\Http\Controllers\ApartmentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Mail\MessageReply;
use Illuminate\Support\Facades\Mail;

// Basic pages
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', function () {
    return view('about');
})->name('about');
Route::get('/contact', function () {
    return view('contact');
})->name('contact');
Route::post('/contact-submit', function () {
    DB::table('forms')->insert([
        'name' => request('name'),
        'email' => request('email'),
        'message' => request('message'),
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return back()->with('success', 'Your message has been submitted successfully!');
});

// Admin Dashboard
Route::get('/admindashboard', function () {
    $ownerCount = \App\Models\Owner::count();
    $tenantCount = \App\Models\Tenant::count();
    
    // You might want to adjust these city counts for your application
    $apartmentsAmman = \App\Models\Apartment::where('city', 'Amman')->count();
    $apartmentsZarqa = \App\Models\Apartment::where('city', 'Zarqa')->count();
    $apartmentsIrbid = \App\Models\Apartment::where('city', 'Irbid')->count();
    
    $messageCount = \App\Models\Form::where('replied', false)->count();

    return view('admindashboard.index', compact(
        'ownerCount', 'tenantCount', 'apartmentsAmman', 
        'apartmentsZarqa', 'apartmentsIrbid', 'messageCount'
    ));
})->name('dash');

// Basic dashboard routes
Route::get('/admindashboard/owners', [DashboardController::class, 'owners'])->name('owners');
Route::get('/admindashboard/tenants', [DashboardController::class, 'tenants'])->name('tenants');
Route::get('/admindashboard/apartments', [DashboardController::class, 'apartments'])->name('apartments');
Route::get('/admindashboard/users', [DashboardController::class, 'allUsers'])->name('users');
Route::get('/admindashboard/messages', [DashboardController::class, 'messages'])->name('admin.messages');
Route::post('/admindashboard/messages/{message}/reply', [DashboardController::class, 'replyMessage'])->name('admin.messages.reply');
Route::get('/admin/unreplied-messages', [DashboardController::class, 'getUnrepliedMessages']);

// Admin routes group
Route::prefix('admin')->name('admin.')->group(function () {
    // User routes
    Route::get('users/create', [DashboardController::class, 'createUser'])->name('users.create');
    Route::post('users', [DashboardController::class, 'storeUser'])->name('users.store');

    // Tenant routes
    Route::get('tenants/{tenant}/edit', [DashboardController::class, 'editTenant'])->name('tenants.edit');
    Route::put('tenants/{tenant}', [DashboardController::class, 'updateTenant'])->name('tenants.update');
    Route::delete('tenants/{tenant}', [DashboardController::class, 'destroyTenant'])->name('tenants.destroy');

    // Owner routes
    Route::get('owners/{owner}/edit', [DashboardController::class, 'editOwner'])->name('owners.edit');
    Route::put('owners/{owner}', [DashboardController::class, 'updateOwner'])->name('owners.update');
    Route::delete('owners/{owner}', [DashboardController::class, 'destroyOwner'])->name('owners.destroy');

    // Apartment routes
    Route::get('apartments/{apartment}/edit', [DashboardController::class, 'editApartment'])->name('apartments.edit');
    Route::put('apartments/{apartment}', [DashboardController::class, 'updateApartment'])->name('apartments.update');
    Route::delete('apartments/{apartment}', [DashboardController::class, 'destroyApartment'])->name('apartments.destroy');
    Route::delete('apartment-images/{image}', [DashboardController::class, 'destroyApartmentImage'])->name('apartment-images.destroy');

    // Booking routes
    Route::post('bookings', [DashboardController::class, 'storeBooking'])->name('bookings.store');
    Route::delete('bookings/{booking}', [DashboardController::class, 'destroyBooking'])->name('bookings.destroy');

    // Available date routes
    Route::post('available-dates', [DashboardController::class, 'storeAvailableDate'])->name('available-dates.store');
    Route::get('available-dates/{availableDate}/edit', [DashboardController::class, 'editAvailableDate'])->name('available-dates.edit');
    Route::put('available-dates/{availableDate}', [DashboardController::class, 'updateAvailableDate'])->name('available-dates.update');
    Route::delete('available-dates/{availableDate}', [DashboardController::class, 'destroyAvailableDate'])->name('available-dates.destroy');
});

// Property listings
Route::get('/apartments', function () {
    // To be implemented
    $apartments = \App\Models\Apartment::with(['owner.user', 'images'])->get();
    return view('apartments.index', compact('apartments'));
})->name('apartments.index');


Route::prefix('apartments')->name('apartments.')->group(function () {
    Route::get('/', [ApartmentController::class, 'index'])->name('index');
    Route::get('/create', [ApartmentController::class, 'create'])->name('create');
    Route::post('/', [ApartmentController::class, 'store'])->name('store');
    Route::get('/{apartment}', [ApartmentController::class, 'show'])->name('show');
    Route::get('/{apartment}/edit', [ApartmentController::class, 'edit'])->name('edit');
    Route::put('/{apartment}', [ApartmentController::class, 'update'])->name('update');
});
Route::post('/apartments', [ApartmentController::class, 'store'])->name('apartments.store');


// Authentication routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// User profile routes
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/change-password', [ProfileController::class, 'showChangePasswordForm'])->name('password.change');
    Route::post('/change-password', [ProfileController::class, 'changePassword'])->name('password.update');
});

