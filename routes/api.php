<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\CountryOpportunityController;
use App\Http\Controllers\EventCartController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\EventOrderController;
use App\Http\Controllers\InvestmentController;
use App\Http\Controllers\MemberOrderController;
use App\Http\Controllers\MembershipController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\OpportunityController;
use App\Http\Controllers\OpportunityImageController;
use App\Http\Controllers\OpportunitySectorController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\QrCodeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SectorController;
use App\Http\Controllers\TestimonialController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['auth:sanctum']], function() {

    Route::get('/logout', [AuthController::class, 'logout']);

    /* AUTH */
    Route::prefix('profile')->group(function() {
        Route::get('/', [AuthController::class, 'view']);
        Route::post('/', [AuthController::class, 'update']);
    });
    Route::post('/password', [AuthController::class, 'password']);
    Route::post('/profile-check', [AuthController::class, 'check']);
 
    /* COUNTRY */
    Route::prefix('country')->group(function() {
        Route::get('/', [CountryController::class, 'index']);
        Route::post('/', [CountryController::class, 'store']);
        Route::get('/{id}', [CountryController::class, 'view']);
        Route::post('/{id}', [CountryController::class, 'update']);
        Route::delete('/{id}', [CountryController::class, 'delete']);
    });
    Route::get('/country-view-by-slug', [CountryController::class, 'viewBySlug']);
    Route::get('/country-all', [CountryController::class, 'indexAll']);
    Route::get('/country-opportunity', [CountryOpportunityController::class, 'indexOpportunityByCountry']);
    Route::get('/country-opportunity-sector', [CountryOpportunityController::class, 'indexOpportunityByCountrySector']);
    
    /* EVENT */
    Route::prefix('event')->group(function() {
        Route::get('/', [EventController::class, 'index']);
        Route::post('/', [EventController::class, 'store']);
        Route::get('/{id}', [EventController::class, 'view']);
        Route::post('/{id}', [EventController::class, 'update']);
        Route::delete('/{id}', [EventController::class, 'delete']);
    });
    Route::get('/event-by-num', [EventController::class, 'indexByNum']);

    /* EVENT CART */
    Route::prefix('event-cart')->group(function() {
        Route::get('/', [EventCartController::class, 'index']);
        Route::post('/', [EventCartController::class, 'store']);
        Route::delete('/{id}', [EventCartController::class, 'delete']);
    });
    Route::get('/event-cart-by-token', [EventCartController::class, 'viewByToken']);

    /* EVENT ORDER */
    Route::prefix('event-order')->group(function() {
        Route::get('/', [EventOrderController::class, 'index']);
        Route::post('/', [EventOrderController::class, 'store']);
        Route::get('/{id}', [EventOrderController::class, 'view']);
        Route::post('/{id}', [EventOrderController::class, 'update']);
        Route::delete('/{id}', [EventOrderController::class, 'delete']);
    });
    Route::get('/event-order-by-user', [EventOrderController::class, 'indexByUser']);
    Route::post('/event-order-status', [EventOrderController::class, 'statusUpdate']);

    /* INVESTMENT */
    Route::prefix('investment')->group(function() {
        Route::get('/', [InvestmentController::class, 'index']);
        Route::post('/', [InvestmentController::class, 'store']);
        Route::get('/{id}', [InvestmentController::class, 'view']);
        Route::post('/{id}', [InvestmentController::class, 'update']);
        Route::delete('/{id}', [InvestmentController::class, 'delete']);
    });
    Route::get('/investment-index-by-user', [InvestmentController::class, 'indexByUser']);
    Route::get('/investment-opportunity-view', [InvestmentController::class, 'investmentOpportunityView']);
    Route::post('/investment-status', [InvestmentController::class, 'statusUpdate']);

    /* QR CODE */
    Route::prefix('qrcode')->group(function() {
        Route::get('/', [QrCodeController::class, 'index']);
        Route::post('/', [QrCodeController::class, 'store']);
        Route::get('/{id}', [QrCodeController::class, 'view']);
        Route::delete('/{id}', [QrCodeController::class, 'delete']);
    });
    Route::get('/qrcode-search', [QrCodeController::class, 'search']);
    Route::get('/qrcode-index-by-status', [QrCodeController::class, 'indexByStatus']);
    Route::get('/qrcode-index-by-num', [QrCodeController::class, 'indexByNum']);
    Route::get('/qrcode-index-by-num-status', [QrCodeController::class, 'indexByNumStatus']);
    Route::post('/qrcode-store-by-num', [QrCodeController::class, 'storeByNum']);
    Route::post('/qrcode-assign-user', [QrCodeController::class, 'assignUser']);

    /* MEMBERSHIP */
    Route::prefix('membership')->group(function() {
        Route::get('/', [MembershipController::class, 'index']);
        Route::post('/', [MembershipController::class, 'store']);
        Route::get('/{id}', [MembershipController::class, 'view']);
        Route::post('/{id}', [MembershipController::class, 'update']);
        Route::delete('/{id}', [MembershipController::class, 'delete']);
    });
    Route::get('/membership-all', [MembershipController::class, 'indexAll']);
    Route::get('/membership-by-slug', [MembershipController::class, 'viewBySlug']);
    Route::get('/membership-by-num', [MembershipController::class, 'indexByNum']);

    /* MEMBER ORDER */
    Route::prefix('member-order')->group(function() {
        Route::get('/', [MemberOrderController::class, 'index']);
        Route::post('/', [MemberOrderController::class, 'store']);
        Route::get('/{id}', [MemberOrderController::class, 'view']);
        Route::delete('/{id}', [MemberOrderController::class, 'delete']);
    });
    Route::post('/member-order-status/{id}', [MemberOrderController::class, 'statusUpdate']);
    Route::get('/member-order-by-user', [MemberOrderController::class, 'indexByUser']);
    Route::post('/member-order-checkout', [MemberOrderController::class, 'checkout']);
    Route::post('/member-order-qrcode', [MemberOrderController::class, 'storeQrCode']);

    /* NEWS */
    Route::prefix('news')->group(function() {
        Route::get('/', [NewsController::class, 'index']);
        Route::post('/', [NewsController::class, 'store']);
        Route::get('/{id}', [NewsController::class, 'view']);
        Route::post('/{id}', [NewsController::class, 'update']);
        Route::delete('/{id}', [NewsController::class, 'delete']);
    });

    /* PARTNER */
    Route::prefix('partner')->group(function() {
        Route::get('/', [PartnerController::class, 'index']);
        Route::post('/', [PartnerController::class, 'store']);
        Route::get('/{id}', [PartnerController::class, 'view']);
        Route::post('/{id}', [PartnerController::class, 'update']);
        Route::delete('/{id}', [PartnerController::class, 'delete']);
    });

    /* TESTIMONIAL */
    Route::prefix('testimonial')->group(function() {
        Route::get('/', [TestimonialController::class, 'index']);
        Route::post('/', [TestimonialController::class, 'store']);
        Route::get('/{id}', [TestimonialController::class, 'view']);
        Route::post('/{id}', [TestimonialController::class, 'update']);
        Route::delete('/{id}', [TestimonialController::class, 'delete']);
    });

    /* USER */
    Route::prefix('user')->group(function() {
        Route::get('/', [UserController::class, 'index']);
        Route::post('/', [UserController::class, 'store']);
        Route::get('/{id}', [UserController::class, 'view']);
        Route::post('/{id}', [UserController::class, 'update']);
        Route::delete('/{id}', [UserController::class, 'delete']);
    });
    Route::get('/user-email-search', [UserController::class, 'searchEmail']);
    
    /* OPPORTUNITY */
    Route::prefix('opportunity')->group(function() {
        Route::get('/', [OpportunityController::class, 'index']);
        Route::post('/', [OpportunityController::class, 'store']);
        Route::get('/{id}', [OpportunityController::class, 'view']);
        Route::post('/{id}', [OpportunityController::class, 'update']);
        Route::delete('/{id}', [OpportunityController::class, 'delete']);
    });
    Route::get('/opportunity-view-by-slug', [OpportunityController::class, 'viewBySlug']);
    Route::post('/opportunity-status', [OpportunityController::class, 'statusUpdate']);
    
    /* OPPORTUNITY IMAGES */
    Route::prefix('opportunity-image')->group(function() {
        Route::delete('/{id}', [OpportunityImageController::class, 'delete']);
    });
    
    /* OPPORTUNITY SECTOR */
    Route::prefix('opportunity-sector')->group(function() {
        Route::get('/', [OpportunitySectorController::class, 'index']);
        Route::post('/', [OpportunitySectorController::class, 'store']);
        Route::get('/{id}', [OpportunitySectorController::class, 'view']);
        Route::delete('/{id}', [OpportunitySectorController::class, 'delete']);
    });
    Route::get('/opportunity-sector-all-by-opportunity-id/{id}', [OpportunitySectorController::class, 'indexByOpportunityId']);

    /* ROLE */
    Route::prefix('role')->group(function() {
        Route::get('/', [RoleController::class, 'index']);
        Route::post('/', [RoleController::class, 'store']);
        Route::get('/{id}', [RoleController::class, 'view']);
        Route::post('/{id}', [RoleController::class, 'update']);
        Route::delete('/{id}', [RoleController::class, 'delete']);
    });
    Route::get('/role-view-by-slug', [RoleController::class, 'viewBySlug']);
    Route::get('/role-all', [RoleController::class, 'indexAll']);

    /* SECTOR */
    Route::prefix('sector')->group(function() {
        Route::get('/', [SectorController::class, 'index']);
        Route::post('/', [SectorController::class, 'store']);
        Route::get('/{id}', [SectorController::class, 'view']);
        Route::post('/{id}', [SectorController::class, 'update']);
        Route::delete('/{id}', [SectorController::class, 'delete']);
    });
    Route::get('/sector-view-by-slug', [SectorController::class, 'viewBySlug']);
    Route::get('/sector-all', [SectorController::class, 'indexAll']);
    




});
