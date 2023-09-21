
<?php

use App\Http\Controllers\paymentsController;
use App\Http\Controllers\PlansController;
use App\Http\Controllers\Webhooks\StripeController;
use Faker\Provider\ar_EG\Payment;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClassroomsController;
use App\Http\Controllers\JoinClassroomController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TopicsController;
use App\Http\Controllers\ClassworkController;
use App\Http\Controllers\ClassroomPeopleController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentsController;
use App\Http\Controllers\SubmissionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
// require __DIR__ . '/auth.php';
Route::get('plans',[PlansController::class,'index'])
->name('plans');
Route::prefix('classroom/{classroom}/topics/trashed')
    ->as('topics.')
    ->controller(TopicsController::class)
    ->group(function () {
        Route::get('/', 'trashed')
            ->name('trashed');
        Route::put('/{topic}', 'restore')
            ->name('restore');
        Route::delete('/{topic}', 'forceDelete')
            ->name('force-delete');
    })->middleware('auth');

Route::middleware(['auth:web,admin'])->group(function () {
    Route::get('subscription/{subscription}/checkout',[PaymentController::class,'create'])
        ->name('checkout');
    Route::post('subscriptions',[SubmissionController::class,'store'])
        ->name('subscriptions.store');
    Route::post('payments',[PaymentController::class,'store'])
        ->name('payments.store');
    Route::get('/payments/{supscription}/success',[paymentsController::class,'success'])
        ->name('payments.success');
    Route::get('/payments/{supscription}/cancel',[paymentsController::class,'cancel'])
        ->name('payments.cancel');
    Route::get('change-language/{locale}', [ProfileController::class, 'changeLanguage'])
        ->name('changeLanguage');
        //////////////////////////////////////////////////////////////////
    Route::prefix('/classrooms/trashed')
        ->as('classrooms.')
        ->controller(ClassroomsController::class)
        ->group(function () {
            Route::get('/', 'trashed')
                ->name('trashed');
            Route::put('/{classroom}', 'restore')
                ->name('restore');
            Route::delete('/{classroom}', 'forceDelete')
                ->name('force-delete');
        });
    Route::get('classroom/{classroom}/streams', [ClassroomsController::class, 'streams'])
        ->name('classroom.streams');
    Route::resource('classroom.post', PostController::class);
    Route::get('submissions/{submission}/file', [SubmissionController::class, 'file'])->name('file');
    Route::resource('classroom.classwork', ClassworkController::class)
    ->shallow();
    //shallow-> بتخلي راوت destroy,update,edit,show بدون ما يمرر كلاس رووم باراميتر
    Route::get('/classrooms/{classroom}/join', [JoinClassroomController::class, 'create'])
        ->middleware('signed')
        ->name('classrooms.join');
    Route::post('/classrooms/{classroom}/join', [JoinClassroomController::class, 'store']);
    Route::post('/classrooms/{classroom}/chat', [ClassroomsController::class, 'classrooms.chat']);

    Route::get('/classrooms/{classroom}/people', [ClassroomPeopleController::class, 'index'])
        ->name('classrooms.people'); //invokable Controller
    Route::delete('/classrooms/{classroom}/people', [ClassroomPeopleController::class, 'destroy'])
        ->name('classrooms.people.destroy');

    Route::get('/classroom', [ClassroomsController::class, 'index'])
        ->name('classrooms.index');
    Route::get('/classrooms/create', [ClassroomsController::class, 'create'])
        ->name('classrooms.create');
    Route::post('/classrooms', [ClassroomsController::class, 'store'])
        ->name('classrooms.store');
    //Route::get('/classrooms/create',[ClassroomsController::class ,'index']);رح يرجع التاني لو كانوا نفس الميثود والباث 

    Route::get('/classrooms/{id}/edit', [ClassroomsController::class, 'edit'])
        ->name('classrooms.edit');
    
    Route::get('/classrooms/{id}', [ClassroomsController::class, 'show'])
        ->name('classrooms.show')
        ->where('id', '\d+');
    Route::put('/classrooms/{id}', [ClassroomsController::class, 'update'])
        ->name('classrooms.update')
        ->where('id', '\d+');
    Route::delete('/classrooms/{id}', [ClassroomsController::class, 'destroy'])
        ->name('classrooms.destroy');
   

    Route::post('comments', [CommentsController::class, 'store'])
        ->name('comments.store');
    Route::post('classwork/{classwork}/submissions', [SubmissionController::class, 'store'])
        ->name('submissions.store')
        ->middleware('can:submission.create,classwork'); //with model binding

    Route::resource('classroom.topic', TopicsController::class);
});
Route::get('/', function () {
    return view('welcome');
});



Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth:admin', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::post('/payments/stripe/webhook',[StripeController::class]);