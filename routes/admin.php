<?php
use Illuminate\Support\Facades\Route;

## Controllers
use App\Http\Controllers\Backend\FaqController;
use App\Http\Controllers\Backend\LoginController;
use App\Http\Controllers\Backend\UsersController;
use App\Http\Controllers\Backend\BlogsController;
use App\Http\Controllers\Backend\PagesController;
use App\Http\Controllers\Backend\PopupController;
use App\Http\Controllers\Backend\LeadsController;
use App\Http\Controllers\Backend\SourceController;
use App\Http\Controllers\Backend\ReviewsController;
use App\Http\Controllers\Backend\ReportsController;
use App\Http\Controllers\Backend\RegionsController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\PublisherController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\UserAccessController;
use App\Http\Controllers\Backend\SiteSettingController;
use App\Http\Controllers\Backend\CustomLeadsController;
use App\Http\Controllers\Backend\BulkReportsController;
use App\Http\Controllers\Backend\TransactionsController;
use App\Http\Controllers\Backend\CustomReportsController;
use App\Http\Controllers\Backend\EmailTemplateController;
use App\Http\Controllers\Backend\BulkReportsUploadingController;

/*
|--------------------------------------------------------------------------
| ADMIN Routes
|--------------------------------------------------------------------------
*/
Route::controller(LoginController::class)->group(function () {
    Route::group(['middleware' => 'AdminRedirect'],function()
    {
        Route::get('/', 'index');
    });

    Route::group(['middleware' => ['adminAuth','prevent-back-history']],function()
    {
        Route::get('/auth/login', 'index');  
        Route::post('/login', 'checkLogin');  
        Route::post('/logout', 'logout')->name('logout');  
    });
});


Route::group(['middleware' => ['AdminRedirectIfAuthenticated', 'prevent-back-history']], function()
{
    Route::controller(DashboardController::class)->group(function ()
    {
        Route::get('/dashboard', 'index');
        Route::post('/dashboard', 'index');
    });

    /* Category */
    Route::controller(CategoryController::class)->group(function ()
    {
        Route::group(['prefix' => 'category'], function (){
            Route::get('/',                   'index');
            Route::get('/edit/{id}',          'edit');
            Route::post('/store',             'store');
            Route::get('/create',             'create');
            Route::post('/update/{id}',       'update');
            Route::get('/getData',            'getData');
            Route::post('/destroy/{id}',      'destroy');
            Route::post('/updateStatus/{id}', 'updateStatus');
        });
    });

    /* Publisher */
    Route::controller(PublisherController::class)->group(function ()
    {
        Route::group(['prefix' => 'publisher'], function (){
            Route::get('/',                   'index');
            Route::get('/edit/{id}',          'edit');
            Route::post('/store',             'store');
            Route::get('/create',             'create');
            Route::post('/update/{id}',       'update');
            Route::get('/getData',            'getData');
            Route::post('/destroy/{id}',      'destroy');
            Route::post('/updateStatus/{id}', 'updateStatus');
        });
    });

    /* Reports */
    Route::controller(ReportsController::class)->group(function ()
    {
        Route::group(['prefix' => 'reports'], function (){
            Route::get('/',                   'index');
            Route::get('/edit/{id}',          'edit');
            Route::post('/store',             'store');
            Route::get('/create',             'create');
            Route::post('/update/{id}',       'update');
            Route::get('/getData',            'getData');
            Route::post('/destroy/{id}',      'destroy');
            Route::post('/updateStatus/{id}', 'updateStatus');
        });
    });

    /* Users */
    Route::controller(UsersController::class)->group(function ()
    {
        Route::group(['prefix' => 'users'], function (){
            Route::get('/',                   'index');
            Route::get('/edit/{id}',          'edit');
            Route::post('/store',             'store');
            Route::get('/create',             'create');
            Route::post('/update/{id}',       'update');
            Route::get('/getData',            'getData');
            Route::post('/destroy/{id}',      'destroy');
            Route::post('/updateStatus/{id}', 'updateStatus');
        });
    });

    /* Source */
    Route::controller(SourceController::class)->group(function ()
    {
        Route::group(['prefix' => 'source'], function (){
            Route::get('/',                   'index');
            Route::get('/edit/{id}',          'edit');
            Route::post('/store',             'store');
            Route::get('/create',             'create');
            Route::post('/update/{id}',       'update');
            Route::get('/getData',            'getData');
            Route::post('/destroy/{id}',      'destroy');
            Route::post('/updateStatus/{id}', 'updateStatus');
        });
    });

    /* Blogs */
    Route::controller(BlogsController::class)->group(function ()
    {
        Route::group(['prefix' => 'blogs'], function (){
            Route::get('/',                   'index');
            Route::get('/edit/{id}',          'edit');
            Route::post('/store',             'store');
            Route::get('/create',             'create');
            Route::post('/update/{id}',       'update');
            Route::get('/getData',            'getData');
            Route::post('/destroy/{id}',      'destroy');
            Route::post('/updateStatus/{id}', 'updateStatus');
        });
    });

    /* Reviews */
    Route::controller(ReviewsController::class)->group(function ()
    {
        Route::group(['prefix' => 'reviews'], function (){
            Route::get('/',                   'index');
            Route::get('/edit/{id}',          'edit');
            Route::post('/store',             'store');
            Route::get('/create',             'create');
            Route::post('/update/{id}',       'update');
            Route::get('/getData',            'getData');
            Route::post('/destroy/{id}',      'destroy');
            Route::post('/updateStatus/{id}', 'updateStatus');
        });
    });

    /* Faq */
    Route::controller(FaqController::class)->group(function ()
    {
        Route::group(['prefix' => 'faq'], function (){
            Route::get('/',                   'index');
            Route::get('/edit/{id}',          'edit');
            Route::post('/store',             'store');
            Route::get('/create',             'create');
            Route::post('/update/{id}',       'update');
            Route::get('/getData',            'getData');
            Route::post('/destroy/{id}',      'destroy');
            Route::post('/updateStatus/{id}', 'updateStatus');
        });
    });

    /* Pages */
    Route::controller(PagesController::class)->group(function ()
    {
        Route::group(['prefix' => 'pages'], function (){
            Route::get('/',                   'index');
            Route::get('/edit/{id}',          'edit');
            Route::post('/store',             'store');
            Route::get('/create',             'create');
            Route::post('/update/{id}',       'update');
            Route::get('/getData',            'getData');
            Route::post('/destroy/{id}',      'destroy');
            Route::post('/updateStatus/{id}', 'updateStatus');
        });
    });

    /* Regions */
    Route::controller(RegionsController::class)->group(function ()
    {
        Route::group(['prefix' => 'regions'], function (){
            Route::get('/',                   'index');
            Route::get('/edit/{id}',          'edit');
            Route::get('/view/{id}',          'view');
            Route::post('/store',             'store');
            Route::get('/create',             'create');
            Route::post('/update/{id}',       'update');
            Route::get('/getData',            'getData');
            Route::post('/destroy/{id}',      'destroy');
            Route::post('/updateStatus/{id}', 'updateStatus');

            ## Regions country
            Route::post('/add_country',              'add_country');
            Route::post('/update_country',           'update_country');
            Route::get('/getCountryData',            'getCountryData');
            Route::post('/destroy_country/{id}',     'destroy_country');
            Route::post('/updateCountryStatus/{id}', 'updateCountryStatus');
        });
    });

    /* Popup */
    Route::controller(PopupController::class)->group(function ()
    {
        Route::group(['prefix' => 'popup'], function (){
            Route::get('/',                   'index');
            Route::get('/edit/{id}',          'edit');
            Route::post('/store',             'store');
            Route::get('/create',             'create');
            Route::post('/update/{id}',       'update');
            Route::get('/getData',            'getData');
            Route::post('/destroy/{id}',      'destroy');
            Route::post('/updateStatus/{id}', 'updateStatus');
        });
    });

    /* Email Template */
    Route::controller(EmailTemplateController::class)->group(function ()
    {
        Route::group(['prefix' => 'email_templates'], function (){
            Route::get('/',                   'index');
            Route::get('/edit/{id}',          'edit');
            Route::post('/store',             'store');
            Route::get('/create',             'create');
            Route::post('/update/{id}',       'update');
            Route::get('/getData',            'getData');
            Route::post('/destroy/{id}',      'destroy');
            Route::post('/updateStatus/{id}', 'updateStatus');
        });
    });

    /* Leads */
    Route::controller(LeadsController::class)->group(function ()
    {
        Route::group(['prefix' => 'leads'], function (){
            Route::get('/',                   'index')->name('leads');
            Route::get('/view/{id}',          'view');
            Route::post('/update/{id}',       'update');
            Route::get('/getData',            'getData');
            Route::post('/updateStatus/{id}', 'updateStatus');
            Route::post('/assign_lead',       'assign_lead');
            Route::post('/download_reports',  'download_reports');
        });
    });

    /* Custom Leads */
    Route::controller(CustomLeadsController::class)->group(function ()
    {
        Route::group(['prefix' => 'custom_leads'], function (){
            Route::get('/',                   'index')->name('custom_leads');
            Route::get('/view/{id}',          'view');
            Route::post('/update/{id}',       'update');
            Route::get('/getData',            'getData');
            Route::post('/updateStatus/{id}', 'updateStatus');
            Route::post('/assign_source',     'assign_source');
            Route::post('/download_reports',  'download_reports');
        });
    });

    /* Custom reports download */
    Route::controller(CustomReportsController::class)->group(function ()
    {
        Route::group(['prefix' => 'custom_reports_download'], function (){
            Route::get('/',                   'index');
            Route::get('/view/{id}',          'view');
            Route::post('/update/{id}',       'update');
            Route::get('/getData',            'getData');
            Route::post('/updateStatus/{id}', 'updateStatus');
            Route::post('/assign_source',     'assign_source');
            Route::post('/download_reports',  'download_reports');
        });
    });
    
    /* Site setting */
    Route::controller(SiteSettingController::class)->group(function ()
    {
        Route::group(['prefix' => 'site_setting'], function (){
            Route::get('/',             'index');
            Route::post('/update', 'update');
        });
    });

    /* Transactions */
    Route::controller(TransactionsController::class)->group(function ()
    {
        Route::group(['prefix' => 'transactions'], function (){
            Route::get('/',              'index')->name('transactions');
            Route::get('/view/{id}',     'view');
            Route::post('/update/{id}',  'update');
            Route::get('/getData',       'getData');
            Route::post('/destroy/{id}', 'destroy');
        });
    });

    /* Bulk reports download */
    Route::controller(BulkReportsController::class)->group(function ()
    {
        Route::group(['prefix' => 'bulk_reports_download'], function (){
            Route::get('/',                   'index');
            Route::get('/view/{id}',          'view');
            Route::post('/update/{id}',       'update');
            Route::get('/getData',            'getData');
            Route::post('/updateStatus/{id}', 'updateStatus');
            Route::post('/assign_source',     'assign_source');
            Route::post('/download_reports',  'download_reports');
        });
    });

    /* Bulk reports uploading */
    Route::controller(BulkReportsUploadingController::class)->group(function ()
    {
        Route::group(['prefix' => 'bulk_reports_uploading'], function (){
            Route::get('/',                          'index');
            Route::get('/getData',                   'getData');
            Route::get('/upload_sheet/{id}',         'upload_sheet');
            Route::get('/dowload_sheet/{id}',        'dowload_sheet');
            Route::post('/upload_report',            'upload_report');
            Route::post('/upload_sheet_report/{id}', 'upload_sheet_report');
        });
    });

    /* User access */
    Route::controller(UserAccessController::class)->group(function ()
    {
        Route::group(['prefix' => 'user_access'], function (){
            Route::get('/',              'index');
            Route::get('/access/{slug}', 'access');
            Route::post('/update_access', 'update_access');
        });
    });
});