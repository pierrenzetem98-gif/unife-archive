<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ZipArchive;

class ExamController extends Controller
{
// app/Http/Controllers/ExamController.php

public function store(Request $request)
{
    $request->validate([
        'subject_id'    => 'required|exists:subjects,id',
        'title'         => 'required|string|max:255',
        'academic_year' => 'required|string|max:20',
        'type'          => 'required|string|max:50',
        'pdf_file'      => 'required|file|mimes:pdf|max:10240',
    ]);

    $path = $request->file('pdf_file')->store('exams', 'public');

    Exam::create([
        'subject_id'    => $request->subject_id,
        'created_by'    => Auth::id(),
        'title'         => $request->title,
        'academic_year' => $request->academic_year,
        'type'          => $request->type,
        'file_path'     => $path,
        'is_community'  => true, // Toute nouvelle soumission est classée en contribution communautaire
    ]);

    return back()->with('success', 'Documento aggiunto alla sezione "Nuove Aggiunte dalla Community"!');
}

    public function download(Exam $exam)
    {
        if (!Storage::disk('public')->exists($exam->file_path)) {
            abort(404, 'File non trovato.');
        }

        $safeTitle = Str::slug($exam->title);
        return Storage::disk('public')->download($exam->file_path, "{$safeTitle}.pdf");
    }

    public function destroy(Exam $exam)
    {
        if (!Auth::user()->isAdmin() && Auth::id() !== $exam->created_by) {
            abort(403, 'Azione non autorizzata.');
        }

        Storage::disk('public')->delete($exam->file_path);
        $exam->delete();

        return back()->with('success', 'Esame eliminato con successo!');
    }

    /**
     * Archive ZIP de tous les examens d'une matière donnée
     */
    public function downloadSubjectZip(Subject $subject)
    {
        $exams = $subject->exams()->get();

        if ($exams->isEmpty()) {
            return back()->withErrors(['error' => 'Nessun PDF disponibile per questa materia.']);
        }

        $zipFileName = 'UniFe_' . Str::slug($subject->name) . '_Archive.zip';
        $tempZipPath = storage_path('app/' . $zipFileName);

        $zip = new ZipArchive;
        if ($zip->open($tempZipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            abort(500, 'Impossibile generare il file ZIP.');
        }

        foreach ($exams as $exam) {
            $diskPath = Storage::disk('public')->path($exam->file_path);
            if (file_exists($diskPath)) {
                $entryName = Str::slug($exam->academic_year . '_' . $exam->type . '_' . $exam->title) . '.pdf';
                $zip->addFile($diskPath, $entryName);
            }
        }

        $zip->close();

        return response()->download($tempZipPath)->deleteFileAfterSend(true);
    }

    /**
     * Archive ZIP globale de toutes les matières d'une année (I, II, ou III)
     */
    public function downloadYearZip(string $year)
    {
        $subjects = Subject::where('year', $year)->with('exams')->get();
        $zipFileName = "UniFe_Anno_{$year}_Completo.zip";
        $tempZipPath = storage_path('app/' . $zipFileName);

        $zip = new ZipArchive;
        if ($zip->open($tempZipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            abort(500, 'Impossibile generare il file ZIP.');
        }

        $hasFiles = false;

        foreach ($subjects as $subject) {
            $folderName = Str::slug($subject->name);
            foreach ($subject->exams as $exam) {
                $diskPath = Storage::disk('public')->path($exam->file_path);
                if (file_exists($diskPath)) {
                    $entryName = $folderName . '/' . Str::slug($exam->title) . '.pdf';
                    $zip->addFile($diskPath, $entryName);
                    $hasFiles = true;
                }
            }
        }

        $zip->close();

        if (!$hasFiles) {
            if (file_exists($tempZipPath)) {
                unlink($tempZipPath);
            }
            return back()->withErrors(['error' => "Nessun file presente per l'anno {$year}."]);
        }

        return response()->download($tempZipPath)->deleteFileAfterSend(true);
    }
}