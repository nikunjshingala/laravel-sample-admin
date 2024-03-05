<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\UserSettingsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TestRouteController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('refresh-csrf', function(){
    session()->regenerate();
    return csrf_token();
})->name('refresh-csrf');
Route::get('logout', [LoginController::class, 'logout'])->name('auth.logout');
Route::post('login',[LoginController::class, 'login']);
Route::post('forgot-password',[LoginController::class, 'forgotPassword'])->name('forgot-password');
Route::view('forgot-password', 'auth.forgot_password')->name('forgot_password')->middleware('guest');
Route::post('recover-password-post',[LoginController::class, 'recoverPassword'])->name('recover-password-post');
Route::get('/reset-password/{token}', function ($token) {
    if(!empty($token)){
        return view('auth.recover_password', ['token' => $token]);
    } else {
        $returnData['status'] = 'error';
        $returnData['msg'] = 'Somthing went wrong please try again later.';
        return redirect()->back()->with($returnData);
    }
})->middleware('guest')->name('password.reset');
Route::get('/', function () {
    if(Auth::check()){
        return redirect('dashboard');
    } else {
        return redirect('login');
    }
});
Route::get('error', [ResourceController::class, 'error'])->name('error');
Route::view('login', 'auth.login')->name('login')->middleware('guest');
Route::get('resource/{d}/{f}',  [ResourceController::class, 'index']);
Route::get('account-setup', [UserController::class, 'accountSetup'])->name('account-setup')->middleware('guest');
Route::post('set-password-post', [UserController::class, 'saveUserPassword'])->name('set-password-post');
Route::group(['middleware' => ['auth','checkPermission']], function () {
    Route::group(['middleware' => ['subscribed']], function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('author/', [AuthorController::class, 'index'])->name('authorIndex');
        Route::post('get/filter-data', [AuthorController::class, 'filterData'])->name('getData');
        Route::get('author/create', [AuthorController::class, 'create'])->name('authorCreate');
        Route::post('author/store', [AuthorController::class, 'store'])->name('storeAuthor');
        Route::get('author/{id}/edit', [AuthorController::class, 'edit'])->name('authorEdit');
        Route::put('author/{id}', [AuthorController::class, 'update'])->name('authorUpdate');
        Route::delete('author/{id}', [AuthorController::class, 'destroy'])->name('authorDelete');
        Route::post('author/toggle-status', [AuthorController::class, 'toggleStatus'])->name('authorToggleStatus');
        
        Route::post('author/custom-action', [AuthorController::class, 'customAction'])->name('custom-action');
        Route::post('author/move-file', [AuthorController::class, 'moveFiles'])->name('authorFileUpload');
        //Uploade file remove(unlink) before uploade
        Route::post('author/unlink-file', [AuthorController::class, 'unlinkFile'])->name('authorFileUnlink');
        Route::post('author/remove-file', [AuthorController::class, 'removeFile'])->name('removeAuthorFiles');
        Route::get('download-author-attachment/{file_name}/{original_name}', [AuthorController::class, 'downloadAttachment'])->name('AuthorDownloadAttachment');
        Route::get('download-dummy-author-csv', [AuthorController::class, 'downloaDummyCSV'])->name('download-dummy-author-csv');
        Route::post('author/import-author-csv', [AuthorController::class, 'importAuthorCSV'])->name('import-author-csv');
        Route::post('author_email_check', [AuthorController::class, 'authorEmailCheck'])->name('author_email_check');
        

        Route::get('user/', [UserController::class, 'index'])->name('userIndex');
        Route::post('get-user/filter-data', [UserController::class, 'filterData'])->name('getUserData');
        Route::get('user/create', [UserController::class, 'create'])->name('userCreate');
        Route::post('user/store', [UserController::class, 'store'])->name('userStore');
        Route::get('user/{id}/edit', [UserController::class, 'edit'])->name('userEdit');
        Route::put('user/{id}', [UserController::class, 'update'])->name('userUpdate');
        Route::delete('user/{id}', [UserController::class, 'destroy'])->name('userDelete');
        Route::post('user/toggle-status', [UserController::class, 'toggleStatus'])->name('userToggleStatus');
        Route::post('user/reset-password', [UserController::class, 'restPasswordFromUser'])->name('userrestPasswordFromUser');
        
        Route::get('/user-settings', [UserSettingsController::class, 'index'])->name('userSettings');
        Route::post('/user-details-update', [UserSettingsController::class, 'userDetailsUpdate'])->name('userDetailsUpdate');
        Route::post('/user-password-change', [UserSettingsController::class, 'userPasswordChange'])->name('userPasswordChange');
        
        Route::get('/route1', [TestRouteController::class, 'route1'])->name('route1');
        Route::get('/route2', [TestRouteController::class, 'route2'])->name('route2');
    });

    Route::get('/show-subscription', [SubscriptionController::class, 'showSubscription'])->name('show-subscription');
    Route::post('/cancel-subscription/', [SubscriptionController::class, 'cancelSubscription'])->name('cancel-subscription');
    Route::post('/subscribe', [SubscriptionController::class, 'processSubscription'])->name('subscribe');
    Route::get('/update-subscription', [SubscriptionController::class, 'updateSubscription'])->name('update-subscription');
    Route::put('/update-subscription', [SubscriptionController::class, 'changeSubscription'])->name('update-subscription');
    
});