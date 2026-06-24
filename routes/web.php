<?php

Route::get('/', 'StorefrontController@home')->name('storefront.home');
Route::get('/shop/{audience:slug}', 'StorefrontController@audience')->name('storefront.audience');
Route::get('/shop/{audience:slug}/{category:slug}', 'StorefrontController@category')->name('storefront.category');
Route::get('/brands/{brand:slug}', 'StorefrontController@brand')->name('storefront.brand');
Route::get('/products/{product:slug}', 'StorefrontController@product')->name('storefront.product');
Route::get('/home', function () {
    if (session('status')) {
        return redirect()->route('admin.home')->with('status', session('status'));
    }

    return redirect()->route('admin.home');
});
 
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
