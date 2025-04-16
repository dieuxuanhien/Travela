<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\frontend\HomeController;
use App\Http\Controllers\frontend\AboutController;
use App\Http\Controllers\frontend\BlogController;
use App\Http\Controllers\frontend\ServiceController;
use App\Http\Controllers\frontend\PackageController;
use App\Http\Controllers\frontend\BookingController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\GoogleLoginController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\FacebookLoginController;
use App\Http\Controllers\frontend\ContactController;
use App\Http\Controllers\frontend\DestinationController;
use App\Http\Controllers\frontend\ExploreTourController;
use App\Http\Controllers\frontend\GalleryController;
use App\Http\Controllers\frontend\GuideController;
use App\Http\Controllers\frontend\TestimonialController;
use App\Http\Controllers\frontend\ErrorController;
use App\Http\Controllers\frontend\ScheduleController;
use App\Http\Controllers\frontend\ChatbotController;
use App\Http\Controllers\AddressController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\frontend\GoMapsController;
use App\Http\Controllers\WeatherController;
use App\Http\Controllers\frontend\ProvinceController;





Route::get('/', [HomeController::class, 'index'])->name('home.index');

Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/service', [ServiceController::class, 'index'])->name('service.index');
Route::get('/package', [PackageController::class, 'index'])->name('package.index');
Route::get('/register', [RegisterController::class, 'index'])->name('register.index');

Route::get('/login', [LoginController::class, 'index'])->name('login.index');
Route::POST('/login-local', [LoginController::class, 'login'])->name('login.login');

Route::post('/register/sub', [RegisterController::class, 'register'])->name('auth.reg');

Route::get('/google/login', [GoogleLoginController::class, 'provider'])->name('google.login');
Route::get('/google/callback', [GoogleLoginController::class, 'callback'])->name('google.login.callback');

Route::get('/facebook/login', [FacebookLoginController::class, 'provider'])->name('facebook.login');
Route::get('/facebook/callback', [FacebookLoginController::class, 'callback'])->name('facebook.login.callback');

Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');

Route::get('/booking', [BookingController::class, 'index'])->name('booking.index');

Route::get('/tour', [ExploreTourController::class, 'index'])->name('tour.index');

Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery.index');

Route::get('/guide', [GuideController::class, 'index'])->name('guide.index');

Route::get('/testimonial', [TestimonialController::class, 'index'])->name('testimonial.index');

Route::get('/testimonial', [TestimonialController::class, 'index'])->name('testimonial.index');


//---------------Schedule---------------//
Route::get('/schedule', [ScheduleController::class, 'index'])->name('schedule.index');
Route::get('/place-details', [ScheduleController::class, 'searfchPlace'])->name('place');

Route::get('/map', [ScheduleController::class, 'map'])->name('map');
Route::get('/build-schedule', [ScheduleController::class, 'build_schedule'])->name('build-schedule');
Route::get('/get-event', [ScheduleController::class, 'getEventAndActivity'])->name('get-event');
Route::get('/directions', [ScheduleController::class, 'getDirections'])->name('directions');
//---------------End Schedule---------------//



//---------------Chatbot---------------//
Route::post('/chatbot', [ChatbotController::class, 'sendMessage'])->name('chatbot');
//---------------End Chatbot---------------//

Route::get('/404', [ErrorController::class, 'index'])->name('404');



//---------------Destination---------------//
Route::get('/destination', [DestinationController::class, 'index'])->name('destination.index');
Route::get('/destination-detail/{id}', [DestinationController::class, 'detail'])->name('destination.detail');

Route::get('/about', [AboutController::class, 'index'])->name('about.index');


Route::get('/map-route', [ScheduleController::class, 'showRoute'])->name('map.route');

Route::get('/map-search', [ScheduleController::class, 'searchAddress'])->name('map.search');


//---------------Logout---------------//
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');




Route::get('/api/provinces', [ProvinceController::class, 'getProvinces']);
Route::get('/districts/{provinceId}', [ProvinceController::class, 'getDistricts']);
Route::get('/wards/{districtId}', [ProvinceController::class, 'getWards']);
Route::get('/api/search', [ScheduleController::class, 'search']);


Route::get('/weather', [WeatherController::class, 'getWeather']);
