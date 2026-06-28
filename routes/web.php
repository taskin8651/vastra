<?php
use App\Http\Controllers\Frontend\AudienceController;
use App\Http\Controllers\Frontend\CategoryProductController;
use App\Http\Controllers\Frontend\BrandController;
use App\Http\Controllers\Frontend\ProductDetailController;
use App\Http\Controllers\Frontend\CartController;


Route::get('/product/{product:slug}', [ProductDetailController::class, 'show'])
    ->name('frontend.products.show');


Route::prefix('shop')->name('frontend.')->group(function () {

    // Example: /shop/men
    Route::get('/{audience:slug}', [AudienceController::class, 'show'])
        ->name('audience.show');

    // Example: /shop/men/shirts
    Route::get('/{audience:slug}/{category:slug}', [CategoryProductController::class, 'show'])
        ->name('category.products');

});


Route::prefix('brands')->name('frontend.brands.')->group(function () {

    Route::get('/', [BrandController::class, 'index'])
        ->name('index');

    Route::get('/{brand:slug}', [BrandController::class, 'show'])
        ->name('show');

});


Route::get('/product/{product:slug}', [ProductDetailController::class, 'show'])
    ->name('frontend.products.show');
Route::get('/home', function () {
    if (session('status')) {
        return redirect()->route('admin.home')->with('status', session('status'));
    }

    return redirect()->route('admin.home');
});


Route::get('/cart', [CartController::class, 'index'])
    ->name('frontend.cart.index');

Route::post('/cart/add/{product:slug}', [CartController::class, 'add'])
    ->name('frontend.cart.add');

Route::patch('/cart/update/{key}', [CartController::class, 'update'])
    ->name('frontend.cart.update');

Route::delete('/cart/remove/{key}', [CartController::class, 'remove'])
    ->name('frontend.cart.remove');
 
Auth::routes();

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth']], function () {
    Route::get('/', 'HomeController@index')->name('home');
    // Permissions
    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
    Route::resource('permissions', 'PermissionsController');

    // Roles
    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
    Route::resource('roles', 'RolesController');

    // Users
    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
    Route::resource('users', 'UsersController');

    // Audit Logs
    Route::resource('audit-logs', 'AuditLogsController', ['except' => ['create', 'store', 'edit', 'update', 'destroy']]);

    // Store catalogue
    Route::resource('audiences', 'AudiencesController')->except('show');
    Route::resource('categories', 'CategoriesController')->except('show');
    Route::resource('brands', 'BrandsController')->except('show');
    Route::resource('products', 'ProductsController')->except('show');

    
});
Route::group(['prefix' => 'profile', 'as' => 'profile.', 'namespace' => 'Auth', 'middleware' => ['auth']], function () {
    // Change password
    if (file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php'))) {
        Route::get('password', 'ChangePasswordController@edit')->name('password.edit');
        Route::post('password', 'ChangePasswordController@update')->name('password.update');
        Route::post('profile', 'ChangePasswordController@updateProfile')->name('password.updateProfile');
        Route::post('profile/destroy', 'ChangePasswordController@destroy')->name('password.destroyProfile');
    }
});
