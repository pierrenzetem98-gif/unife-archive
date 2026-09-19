<?php

use App\Http\Controllers\SubjectController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserApprovalController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('subjects.index');
});

// Page d'attente d'approbation (Accessible aux utilisateurs connectés non approuvés)
Route::get('/in-attesa-approvazione', function () {
    return view('auth.approval-pending');
})->middleware('auth')->name('approval.notice');

// Espace sécurisé (Requiert Authentification ET Approbation)
Route::middleware(['auth', 'approved'])->group(function () {

    Route::get('/dashboard', [SubjectController::class, 'index'])->name('dashboard');
    Route::get('/materie', [SubjectController::class, 'index'])->name('subjects.index');
    Route::get('/materie/{subject}', [SubjectController::class, 'show'])->name('subjects.show');

    // Téléchargements et gestion des examens
    Route::post('/esami/store', [ExamController::class, 'store'])->name('exams.store');
    Route::get('/esami/{exam}/scarica', [ExamController::class, 'download'])->name('exams.download');
    Route::delete('/esami/{exam}', [ExamController::class, 'destroy'])->name('exams.destroy');

    // Archives ZIP
    Route::get('/materie/{subject}/scarica-zip', [ExamController::class, 'downloadSubjectZip'])->name('subjects.zip');
    Route::get('/archivio/anno/{year}/scarica-zip', [ExamController::class, 'downloadYearZip'])->name('years.zip');
    Route::get('/archivio/totale/scarica-zip', [ExamController::class, 'downloadFullArchiveZip'])->name('archive.full.zip');

    // Profil utilisateur
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ============================================================
    // Espace Administration (Gestion des approbations + Validation examens)
    // ============================================================
    Route::prefix('admin')->name('admin.')->group(function () {

        // --- Gestion des approbations d'utilisateurs ---
        Route::get('/approvazioni', [UserApprovalController::class, 'index'])->name('approvals.index');
        Route::post('/approvazioni/{user}/approva', [UserApprovalController::class, 'approve'])->name('approvals.approve');
        Route::delete('/approvazioni/{user}/rifiuta', [UserApprovalController::class, 'destroy'])->name('approvals.destroy');

        // --- Validation / Ufficializzazione d'un examen ---
        Route::patch('/esami/{exam}/ufficializza', [UserApprovalController::class, 'makeOfficial'])->name('exams.make-official');

    });
});

require __DIR__.'/auth.php';