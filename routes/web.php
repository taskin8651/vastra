<?php
use App\Http\Controllers\Frontend\AudienceController;
use App\Http\Controllers\Frontend\CategoryProductController;
use App\Http\Controllers\Frontend\BrandController;
use App\Http\Controllers\Frontend\ProductDetailController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\AddressController;
use App\Http\Controllers\Frontend\CheckoutController;
use App\Http\Controllers\Frontend\MyOrderController;
use App\Http\Controllers\Frontend\ReturnRequestController;
use App\Http\Controllers\Frontend\WishlistController;
use App\Http\Controllers\Frontend\TrialController;

Route::middleware('auth')->group(function () {
    Route::get('/my-trials', [TrialController::class, 'index'])
        ->name('frontend.trials.index');
});

Route::middleware('auth')->group(function () {
    Route::get('/wishlist', [WishlistController::class, 'index'])
        ->name('frontend.wishlist.index');

    Route::post('/wishlist/{product:slug}', [WishlistController::class, 'store'])
        ->name('frontend.wishlist.store');

    Route::delete('/wishlist/{product:slug}', [WishlistController::class, 'destroy'])
        ->name('frontend.wishlist.destroy');

    Route::post('/wishlist/{product:slug}/toggle', [WishlistController::class, 'toggle'])
        ->name('frontend.wishlist.toggle');
});

Route::middleware('auth')->group(function () {
    Route::get('/my-orders/{order}/return', [ReturnRequestController::class, 'create'])
        ->name('frontend.returns.create');

    Route::post('/my-orders/{order}/return', [ReturnRequestController::class, 'store'])
        ->name('frontend.returns.store');
});


Route::middleware('auth')->group(function () {
    Route::get('/my-orders', [MyOrderController::class, 'index'])
        ->name('frontend.orders.index');

        Route::get('/my-orders/{order}', [MyOrderController::class, 'show'])
        ->name('frontend.orders.show');

    Route::patch('/my-orders/{order}/cancel', [MyOrderController::class, 'cancel'])
        ->name('frontend.orders.cancel');
});


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
    Route::post('/cart/delivery-method', [CartController::class, 'deliveryMethod'])
    ->name('frontend.cart.delivery-method');
 


Route::middleware('auth')->group(function () {

    Route::get('/address', [AddressController::class, 'index'])
        ->name('frontend.address.index');

    Route::get('/address/create', [AddressController::class, 'create'])
        ->name('frontend.address.create');

    Route::post('/address', [AddressController::class, 'store'])
        ->name('frontend.address.store');

    Route::get('/address/{address}/edit', [AddressController::class, 'edit'])
        ->name('frontend.address.edit');

    Route::put('/address/{address}', [AddressController::class, 'update'])
        ->name('frontend.address.update');

    Route::post('/address/{address}/select', [AddressController::class, 'select'])
        ->name('frontend.address.select');

    Route::delete('/address/{address}', [AddressController::class, 'destroy'])
        ->name('frontend.address.destroy');


        
});




Route::middleware('auth')->group(function () {
    Route::get('/payment', [CheckoutController::class, 'payment'])
        ->name('frontend.checkout.payment');

    Route::post('/place-order', [CheckoutController::class, 'placeOrder'])
        ->name('frontend.checkout.place-order');

    Route::get('/order-success/{order}', [CheckoutController::class, 'success'])
        ->name('frontend.checkout.success');
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

    // User Addresses
Route::get('user-addresses', 'UserAddressController@index')
    ->name('user-addresses.index');

Route::get('user-addresses/{address}/edit', 'UserAddressController@edit')
    ->name('user-addresses.edit');

Route::put('user-addresses/{address}', 'UserAddressController@update')
    ->name('user-addresses.update');

Route::delete('user-addresses/{address}', 'UserAddressController@destroy')
    ->name('user-addresses.destroy');

    // Return Requests
Route::get('return-requests', 'ReturnRequestsController@index')
    ->name('return-requests.index');

Route::get('return-requests/{returnRequest}', 'ReturnRequestsController@show')
    ->name('return-requests.show');

Route::put('return-requests/{returnRequest}/status', 'ReturnRequestsController@updateStatus')
    ->name('return-requests.updateStatus');
    

    
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
