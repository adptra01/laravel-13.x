<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\MerchantController as AdminMerchantController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;
use App\Http\Controllers\Customer\InvoiceController as CustomerInvoiceController;
use App\Http\Controllers\Customer\MerchantController as CustomerMerchantController;
use App\Http\Controllers\Customer\OrderController as CustomerOrderController;
use App\Http\Controllers\Customer\ProfileController as CustomerProfileController;
use App\Http\Controllers\Customer\ReviewController as CustomerReviewController;
use App\Http\Controllers\Customer\SearchController;
use App\Http\Controllers\Merchant\DashboardController as MerchantDashboardController;
use App\Http\Controllers\Merchant\InvoiceController as MerchantInvoiceController;
use App\Http\Controllers\Merchant\MenuController;
use App\Http\Controllers\Merchant\OrderController as MerchantOrderController;
use App\Http\Controllers\Merchant\ProfileController as MerchantProfileController;
use App\Http\Controllers\Merchant\ReportController;
use App\Http\Controllers\Merchant\ReviewController as MerchantReviewController;
use App\Http\Controllers\UserController;
use App\Models\Menu;
use App\Models\Merchant;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $featuredMerchants = Merchant::where('verification_status', 'verified')
        ->whereHas('menus')
        ->withCount('menus')
        ->withAvg('reviews', 'rating')
        ->withCount('reviews')
        ->limit(3)
        ->get();

    $popularMenus = Menu::where('is_active', true)
        ->with('merchant')
        ->withCount('orderItems')
        ->orderByDesc('order_items_count')
        ->limit(3)
        ->get();

    $activeMenusCount = Menu::where('is_active', true)->count();

    return view('welcome', [
        'featuredMerchants' => $featuredMerchants,
        'popularMenus' => $popularMenus,
        'activeMenusCount' => $activeMenusCount,
    ]);
})->name('home');

Route::get('/dashboard', function () {
    $user = auth()->user();

    return redirect()->to(match (true) {
        $user->isMerchant() => route('merchant.dashboard', absolute: false),
        $user->isAdmin() => route('admin.dashboard', absolute: false),
        default => route('customer.dashboard', absolute: false),
    });
})->middleware(['auth', 'verified'])->name('dashboard');

Route::resource('users', UserController::class)->except('show')->middleware(['auth', 'verified']);

Route::prefix('merchant')->name('merchant.')->middleware(['auth', 'verified', 'role.merchant'])->group(function () {
    Route::get('/', MerchantDashboardController::class)->name('dashboard');

    Route::post('notifications/read', [MerchantNotificationController::class, 'markAllRead'])
        ->name('notifications.read');

    Route::get('/profile', [MerchantProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [MerchantProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/reapply', [MerchantProfileController::class, 'reapply'])->name('profile.reapply');

    Route::resource('menus', MenuController::class)->except('show');
    Route::patch('menus/{menu}/toggle', [MenuController::class, 'toggle'])->name('menus.toggle');

    Route::get('orders', [MerchantOrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [MerchantOrderController::class, 'show'])->name('orders.show');
    Route::patch('orders/{order}/status', [MerchantOrderController::class, 'updateStatus'])->name('orders.status');
    Route::post('orders/{order}/payments/{payment}/confirm', [MerchantOrderController::class, 'confirmPayment'])->name('payments.confirm');

    Route::get('invoices', [MerchantInvoiceController::class, 'index'])->name('invoices.index');
    Route::get('invoices/{invoice}', [MerchantInvoiceController::class, 'show'])->name('invoices.show');
    Route::get('invoices/{invoice}/download', [MerchantInvoiceController::class, 'download'])->name('invoices.download');

    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/export', [ReportController::class, 'exportCsv'])->name('reports.export');

    Route::get('ratings', [MerchantReviewController::class, 'index'])->name('ratings.index');
});

Route::prefix('customer')->name('customer.')->middleware(['auth', 'verified', 'role.customer'])->group(function () {
    Route::get('/', CustomerDashboardController::class)->name('dashboard');

    Route::get('search', SearchController::class)->name('search');
    Route::get('merchants/{merchant}', [CustomerMerchantController::class, 'show'])->name('merchants.show');

    Route::get('orders', [CustomerOrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [CustomerOrderController::class, 'show'])->name('orders.show');
    Route::get('menus/{menu}/order', [CustomerOrderController::class, 'create'])->name('orders.create');
    Route::post('menus/{menu}/order', [CustomerOrderController::class, 'store'])->name('orders.store');
    Route::post('orders/{order}/pay', [CustomerOrderController::class, 'pay'])->name('orders.pay');
    Route::post('orders/{order}/cancel', [CustomerOrderController::class, 'cancel'])->name('orders.cancel');

    Route::get('orders/{order}/review', [CustomerReviewController::class, 'create'])->name('reviews.create');
    Route::post('orders/{order}/review', [CustomerReviewController::class, 'store'])->name('reviews.store');

    Route::get('invoices', [CustomerInvoiceController::class, 'index'])->name('invoices.index');
    Route::get('invoices/{invoice}', [CustomerInvoiceController::class, 'show'])->name('invoices.show');
    Route::get('invoices/{invoice}/download', [CustomerInvoiceController::class, 'download'])->name('invoices.download');

    Route::get('profile', [CustomerProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [CustomerProfileController::class, 'update'])->name('profile.update');
});

Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified', 'role.admin'])->group(function () {
    Route::get('/', AdminDashboardController::class)->name('dashboard');

    Route::get('settings', [AdminSettingController::class, 'edit'])->name('settings.edit');
    Route::put('settings', [AdminSettingController::class, 'update'])->name('settings.update');

    Route::get('merchants', [AdminMerchantController::class, 'index'])->name('merchants.index');
    Route::post('merchants/{merchant}/approve', [AdminMerchantController::class, 'approve'])->name('merchants.approve');
    Route::post('merchants/{merchant}/reject', [AdminMerchantController::class, 'reject'])->name('merchants.reject');
    Route::post('merchants/{merchant}/reapply', [AdminMerchantController::class, 'reapply'])->name('merchants.reapply');
});

require __DIR__.'/auth.php';
