<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SubmissionController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\LanguageController;


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

Route::get('/', function () {
    return view('home');
})->name('home');


// Route::get('/', [HomeController::class, 'index'])->name('home');

// Route::get('/admin', [HomeController::class, 'admin'])
//      ->middleware(['auth', 'verified'])
//      ->name('admin.dashboard');


//  principal content
Route::get('/home', function () {
    return view('home');
})->name('home');

Route::get('/about', function () {
    return view('about');
})->name('about');

// Routes pour le contact
Route::get('/contact', [ContactController::class, 'showForm'])->name('contact');
Route::post('/contact', [ContactController::class, 'submitForm'])->name('contact.submit');

// Submission part

Route::get('/submission', function () {
    return view('submission');
})->name('submission');

Route::get('/submission/create', [SubmissionController::class, 'create'])
    ->name('submission.create');

Route::post('/submission', [SubmissionController::class, 'store'])
    ->name('submission.store');

Route::get('/submission/success/{submission_id}', [SubmissionController::class, 'success'])
    ->name('submission.success');


// Authentification  part
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);



// Routes protégées nécessitant une authentification
Route::middleware(['auth'])->group(function () {
    // Route du tableau de bord
    Route::get('/dashboard', function () {
        if (auth()->user()->role !== 'admin') {
            return redirect()->route('home')->with('error', 'Accès non autorisé.');
        }
        return view('dashboard');
    })->name('dashboard');

    // Routes d'administration
    Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => 'auth'], function () {
        Route::get('/submissions', [App\Http\Controllers\SubmissionController::class, 'index'])->name('submissions.index');
        Route::get('/submissions/{submission}', [App\Http\Controllers\SubmissionController::class, 'show'])->name('submissions.show');
        Route::put('/submissions/{submission}/status', [App\Http\Controllers\SubmissionController::class, 'updateStatus'])->name('submissions.updateStatus');
        Route::delete('/submissions/{submission}', [App\Http\Controllers\SubmissionController::class, 'destroy'])->name('submissions.destroy');
        Route::get('/submissions/{submission}/download/{fileType}/{index?}', [App\Http\Controllers\SubmissionController::class, 'downloadFile'])->name('submissions.downloadFile');
        Route::get('/submissions/{submission}/view/{fileType}/{index?}', [App\Http\Controllers\SubmissionController::class, 'viewFile'])->name('submissions.viewFile');
    });
});


// Routes suivi de dossier
Route::get('/filetracking', [SubmissionController::class, 'trackingForm'])->name('filetracking');
Route::post('/filetracking/search', [SubmissionController::class, 'searchSubmission'])->name('filetracking.search');
Route::get('/filetracking/{submission_id}', [SubmissionController::class, 'tracking'])->name('filetracking.show');
Route::get('/submission/{submission}/download', [SubmissionController::class, 'downloadSubmission'])->name('submission.download');

// Routes pour le langue
Route::get('/lang/{lang}', [LanguageController::class, 'switch'])->name('language.switch');
Route::post('/lang/switch', [LanguageController::class, 'switchPost'])->name('language.switch.post');

// Route de test pour la langue
// Route::get('/test-lang', function () {
//     return view('test-lang');
// })->name('test.lang');

// Route pour l'exemple de traduction
// Route::get('/example-translation', function () {
//     return view('example-translation');
// })->name('example.translation');