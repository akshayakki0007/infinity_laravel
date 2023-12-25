<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Frontend\IndexController;
use App\Http\Controllers\Frontend\SitemapController;
use App\Http\Controllers\Frontend\ReportsController;
use App\Http\Controllers\Frontend\PaymentController;
use App\Http\Controllers\Frontend\ThankYouController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

## tesing mail
Route::get('/testing', function () {
    Mail::send('Emails.test', [], function($message)
    {
        $message->to('akshay@dcodessolutions.com', 'akshay')->subject('Welcome!');
    });
    echo "test mail";
});

Route::group(['middleware' => ['FrontendRedirectIfAuthenticated']], function()
{
    Route::controller(SitemapController::class)->group(function () {
        Route::get('/sitemap.xml', 'index');
        Route::get('/pages.xml',    'pages');
        Route::get('/reports.xml',  'reports');
        Route::get('/report-page-{any}.xml',  'report_page');
    });

    Route::controller(ReportsController::class)->group(function () {
        Route::get('/all-reports',               'index');
        Route::get('/report/{slug}',             'report');
        Route::get('/category/{slug}',           'category');
        Route::get('/request_sample.php',        'request_sample');
        Route::get('/ask_for_discount.php',      'ask_for_discount');
        Route::get('/enquiry_before_buying.php', 'enquiry_before_buying');
        Route::post('/request_form',             'request_form');
    });

    Route::controller(PaymentController::class)->group(function () {
        Route::get('/checkout',       'checkout');
        Route::post('/checkout_form', 'checkout_form');

        ## stripe
        Route::get('payment/stripe_success', 'stripeSuccess')->name('stripeSuccess');
        Route::get('payment/stripe_cancel',  'stripeCancel')->name('stripeCancel');

        ## paypal
        Route::get('payment/paypal_success', 'paypalSuccess')->name('paypalSuccess');
        Route::get('payment/paypal_cancel',  'paypalCancel')->name('paypalCancel');
    });

    Route::controller(ThankYouController::class)->group(function () {
        Route::get('/thank_you.php',                      'index');
        Route::get('/request_sample_thankyou.php',        'index');
        Route::get('/ask_for_discount_thankyou.php',      'index');
        Route::get('/enquiry_before_buying_thankyou.php', 'index');

        Route::get('/payment_thank_you.php', 'payment_thank');
        Route::get('/payment_failed.php',    'payment_failed');
    });

    Route::controller(IndexController::class)->group(function () {
        Route::get('/',            'index')->name('home');
        Route::get('/blogs',       'blogs');
        Route::get('/blog/{id}',   'single_blog');
        Route::get('/{pages}',     'pages');
        Route::post('/contact_us', 'contact_us');
        Route::post('/search',     'search');
    });
});