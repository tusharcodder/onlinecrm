<?php
use Illuminate\Support\Facades\Route;

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
Route::get('/clear-cache-all', function() {
    Artisan::call('cache:clear');
	Artisan::call('optimize:clear');
	Artisan::call('config:clear');
	Artisan::call('route:clear');
	Artisan::call('view:clear');
    dd("Cache Clear All");
});

// reset password false and true
Auth::routes(['reset' => false]);
Route::get('/autocomplete/{table}/{column}', 'CommonController@autocomplete')->name('autocomplete');

Route::get('/logged-in-devices', 'LoggedInDeviceManager@index')
		->name('logged-in-devices.list')
		->middleware('auth');

Route::get('/logout/all', 'LoggedInDeviceManager@logoutAllDevices')
		->name('logged-in-devices.logoutAll')
		->middleware('auth');

Route::get('/logout/{device_id}', 'LoggedInDeviceManager@logoutDevice')
		->name('logged-in-devices.logoutSpecific')
		->middleware('auth');

Route::group(['middleware' => ['auth']], function() {
	
	Route::get('/', function () {
		return redirect()->route('home');
	});
	
	Route::get('dashboard', 'HomeController@index')->name('home');
	Route::get('salechartdetails', 'HomeController@saleChartDetails')->name('salechartdetails');
	
    Route::resource('roles','RoleController');
	Route::resource('permissions','PermissionController');
	Route::resource('users','UserController');	
	Route::resource('vendorss','VendorController');
	Route::resource('marketplaces','MarketPlaceController');
	Route::resource('suppliers','SupplierController');
	Route::resource('bindings','BindingController');
	Route::resource('currencies','CurrenciesController');
	Route::resource('vendorstocks','VendorStockController');
	Route::resource('warehouse','WarehouseController');
	Route::resource('skudetails','SkuDetailController');
	Route::resource('customerorders','CustomerOrderController');
	
	Route::get('skucode-detail-import-export', 'SKUDetailController@detailImportexport')->name('skucode-detail-import-export');
	Route::post('skucodedetailimport', 'SKUDetailController@import')->name('skucodedetailimport');
	Route::post('skucodedetailexport', 'SKUDetailController@export')->name('skucodedetailexport');

	Route::get('vendor-stock-import-export', 'VendorStockController@stockImportExport')->name('vendor-stock-import-export');
	Route::post('vendorstockexport', 'VendorStockController@export')->name('vendorstockexport');
	Route::post('vendorstockimport', 'VendorStockController@import')->name('vendorstockimport');
	Route::delete('deletevendorstockall', 'VendorStockController@deleteVendorStockAll')->name('deletevendorstockall');
	
	Route::get('customer-order-import-export', 'CustomerOrderController@customerOrderImportExport')->name('customer-order-import-export');
	Route::post('customerorderexport', 'CustomerOrderController@export')->name('customerorderexport');
	Route::post('customerorderimport', 'CustomerOrderController@import')->name('customerorderimport');

	
	// Route::get('performances-import-export', 'PerformanceController@performancesImportExport')->name('performances-import-export');
	// Route::post('performancesexport', 'PerformanceController@export')->name('performancesexport');
	// Route::post('performancesimport', 'PerformanceController@import')->name('performancesimport');
	// Route::delete('deleteperformancesall', 'PerformanceController@deletePerformancesAll')->name('deleteperformancesall');

	Route::get('loadvendor/{val}', 'CommonController@getVendor')->name('loadvendor');
	Route::get('loadaggregatorvendor/{val}/{type}', 'CommonController@getAggregatorVendor')->name('loadaggregatorvendor');
	
	Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
    Route::post('register', 'Auth\RegisterController@register');
	
	Route::get('profile/{id}', 'UserController@showProfile')->name('profile');
    Route::patch('profile/{id}/update', ['as' => 'profile.update', 'uses' => 'UserController@updateProfile']);
    Route::patch('profile/{id}/updatepassword', ['as' => 'profile.password', 'uses' => 'UserController@updatePassword']);
});
