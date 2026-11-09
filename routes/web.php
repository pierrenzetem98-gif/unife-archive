<?php

use App\Http\Controllers\SubjectController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserApprovalController;
use App\Http\Controllers\Auth\ActivationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('subjects.index');
});

// ============================================================
// Comptes en attente (connectés mais non approuvés)
// ============================================================
Route::middleware('auth')->group(function () {

    Route::get('/in-attesa-approvazione', function () {
        $user = auth()->user();

        if ($user->isAdmin() || $user->isApproved()) {
            return redirect()->route('subjects.index');
        }

        return view('auth.approval-pending');
    })->name('approval.notice');

    // Saisie du code d'activation reçu sur Instagram (10 essais/minute max)
    Route::post('/in-attesa-approvazione/codice', [ActivationController::class, 'store'])
        ->middleware('throttle:10,1')
        ->name('activation.store');
});

// ============================================================
// Espace sécurisé (authentification ET approbation)
// ============================================================
Route::middleware(['auth', 'approved'])->group(function () {

    Route::get('/dashboard', [SubjectController::class, 'index'])->name('dashboard');
    Route::get('/materie', [SubjectController::class, 'index'])->name('subjects.index');
    Route::get('/materie/{subject}', [SubjectController::class, 'show'])->name('subjects.show');

    // Examens
    Route::post('/esami/store', [ExamController::class, 'store'])->name('exams.store');
    Route::get('/esami/{exam}/scarica', [ExamController::class, 'download'])->name('exams.download');
    Route::delete('/esami/{exam}', [ExamController::class, 'destroy'])->name('exams.destroy');

    // Archives ZIP
    Route::get('/materie/{subject}/scarica-zip', [ExamController::class, 'downloadSubjectZip'])->name('subjects.zip');
    Route::get('/archivio/anno/{year}/scarica-zip', [ExamController::class, 'downloadYearZip'])->name('years.zip');
    Route::get('/archivio/totale/scarica-zip', [ExamController::class, 'downloadFullArchiveZip'])->name('archive.full.zip');

    // Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ============================================================
    // Administration — réservée aux comptes admin
    // ============================================================
    Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {

        // Approbations d'utilisateurs
        Route::get('/approvazioni', [UserApprovalController::class, 'index'])->name('approvals.index');
        Route::post('/approvazioni/{user}/approva', [UserApprovalController::class, 'approve'])->name('approvals.approve');
        Route::post('/approvazioni/{user}/genera-codice', [UserApprovalController::class, 'generateCode'])->name('approvals.generate-code');
        Route::delete('/approvazioni/{user}/rifiuta', [UserApprovalController::class, 'destroy'])->name('approvals.destroy');

        // Ufficializzazione d'un examen
        Route::patch('/esami/{exam}/ufficializza', [UserApprovalController::class, 'makeOfficial'])->name('exams.make-official');
    });
});

require __DIR__.'/auth.php';

// La route /installa-database a été supprimée (faille critique).
