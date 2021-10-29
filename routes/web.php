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
Auth::routes(['reset' => true]);
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
	Route::resource('buyers','BuyerController');
	Route::resource('vendorss','VendorController');
	Route::resource('discounts','DiscountController');
	Route::resource('stocks','StockController');
	Route::resource('bcstocks','BCStockController');
	Route::resource('bcsales','BCSaleController');
	Route::resource('sales','SaleController');
	Route::resource('performances','PerformanceController');
	Route::resource('stockthreshold','StockReminderController');
	Route::resource('gstslab','GstslabController');
	
	// Route to add/Update stock using Bar Code devide.
	Route::post('bcstockscreate', 'BCStockController@getStock')->name('bcstockscreate');
	Route::post('savebcstocks', 'BCStockController@saveStock')->name('savebcstocks');
	Route::post('deletebcstock', 'BCStockController@deletebcstock')->name('deletebcstock');
	
	// Route to add/Update sale using Bar Code devide.
	Route::post('bcsalescreate', 'BCSaleController@getSale')->name('bcsalescreate');
	Route::post('savebcsales', 'BCSaleController@saveSale')->name('savebcsales');
	Route::post('deletebcsale', 'BCSaleController@deleteBCSale')->name('deletebcsale');
	
	Route::get('performances-import-export', 'PerformanceController@performancesImportExport')->name('performances-import-export');
	Route::post('performancesexport', 'PerformanceController@export')->name('performancesexport');
	Route::post('performancesimport', 'PerformanceController@import')->name('performancesimport');
	Route::delete('deleteperformancesall', 'PerformanceController@deletePerformancesAll')->name('deleteperformancesall');

	Route::get('discount-import-export', 'DiscountController@discountImportExport')->name('discount-import-export');
	Route::post('discountexport', 'DiscountController@export')->name('discountexport');
	Route::post('discountimport', 'DiscountController@import')->name('discountimport');
	Route::delete('deletediscountall', 'DiscountController@deleteDiscountAll')->name('deletediscountall');

	Route::get('stock-import-export', 'StockController@stockImportExport')->name('stock-import-export');
	Route::post('stockexport', 'StockController@export')->name('stockexport');
	Route::post('stockimport', 'StockController@import')->name('stockimport');
	Route::delete('deletestockall', 'StockController@deleteStockAll')->name('deletestockall');
	Route::get('stockoutexport', 'StockController@stockOutExport')->name('stock-out-export');
	Route::get('stocklowexport', 'StockController@StockLowExport')->name('stock-low-export');
	
	Route::get('sale-import-export', 'SaleController@saleImportExport')->name('sale-import-export');
	Route::post('saleexport', 'SaleController@export')->name('saleexport');
	Route::post('saleimport', 'SaleController@import')->name('saleimport');
	Route::delete('deletesaleall', 'SaleController@deleteSaleAll')->name('deletesaleall');
	
	// load diccount report blade
	Route::get('discountreport', 'DiscountReportController@index')->name('discountreport');
	Route::get('searchdiscountreport', 'DiscountReportController@search')->name('searchdiscountreport');
	Route::post('downloaddiscountreport', 'DiscountReportController@export')->name('downloaddiscountreport');
	
	// load commission report blade
	Route::get('commissionreport','CommissionreportController@index')->name('commissionreport');
	Route::get('searchcommissionreport', 'CommissionreportController@search')->name('searchcommissionreport');
	Route::post('downloadcommissionreport', 'CommissionreportController@export')->name('downloadcommissionreport');
	Route::post('downloadaggercommissionreport', 'CommissionreportController@export2')->name('downloadaggercommissionreport');
	Route::post('downloadnotbasedreport', 'CommissionreportController@export3')->name('downloadnotbasedreport');
	
	// load Performance report blade
	Route::get('performancereport', 'PerformanceReportController@index')->name('performancereport');
	Route::get('searchperformancereport', 'PerformanceReportController@search')->name('searchperformancereport');
	Route::post('downloadperformancereport', 'PerformanceReportController@export')->name('downloadperformancereport');
	
	// load stock report blade
	Route::get('stockreport', 'StockReportController@index')->name('stockreport');
	Route::get('searchstockreport', 'StockReportController@search')->name('searchstockreport');
	Route::post('downloadstockreport', 'StockReportController@export')->name('downloadstockreport');
	
	//
	Route::get('loadvendor/{val}', 'CommonController@getVendor')->name('loadvendor');
	Route::get('loadaggregatorvendor/{val}/{type}', 'CommonController@getAggregatorVendor')->name('loadaggregatorvendor');
	
	Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
    Route::post('register', 'Auth\RegisterController@register');
	
	Route::get('profile/{id}', 'UserController@showProfile')->name('profile');
    Route::patch('profile/{id}/update', ['as' => 'profile.update', 'uses' => 'UserController@updateProfile']);
    Route::patch('profile/{id}/updatepassword', ['as' => 'profile.password', 'uses' => 'UserController@updatePassword']);
});
