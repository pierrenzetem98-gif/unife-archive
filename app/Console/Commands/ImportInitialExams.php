<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Models\Subject;
use App\Models\Exam;
use App\Models\User;
use Illuminate\Support\Str;

class ImportInitialExams extends Command
{
    protected $signature = 'exams:import';
    protected $description = 'Importa massivamente i file PDF (inclusi quelli nelle sottocartelle come "esame") ignorando i prefissi come "I - " nei nomi.';

    public function handle()
    {
        $admin = User::where('email', 'pierrenzetem98@gmail.com')->first();
        if (!$admin) {
            $this->error('SuperAdmin non trovato.');
            return;
        }

        $sourceDir = base_path('import_exams');

        if (!File::exists($sourceDir)) {
            $this->error("La cartella {$sourceDir} non esiste.");
            return;
        }

        $subjects = Subject::all();
        $directories = File::directories($sourceDir);
        
        if (empty($directories)) {
            $this->error("❌ Non ci sono sottocartelle in 'import_exams'.");
            return;
        }

        $count = 0;

        foreach ($directories as $dir) {
            $dirName = basename($dir);
            
            // Puliamo il nome della cartella rimuovendo caratteri speciali e spazi (es: "I - Fisica" -> "ifisica")
            $cleanDirName = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $dirName));

            // Cerchiamo una materia il cui nome pulito sia *contenuto* nel nome della cartella pulito
            $subject = $subjects->first(function($s) use ($cleanDirName) {
                $cleanDbName = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $s->name));
                return str_contains($cleanDirName, $cleanDbName);
            });

            if ($subject) {
                // allFiles() esplora TUTTE le sottocartelle (inclusa la cartella "esame")
                $files = File::allFiles($dir);
                $pdfFound = false;

                foreach ($files as $file) {
                    if (strtolower($file->getExtension()) === 'pdf') {
                        $pdfFound = true;
                        $filename = $file->getFilename();
                        $cleanTitle = str_replace(['.pdf', '_'], ['', ' '], $filename);
                        
                        $destinationPath = 'exams/' . Str::slug($subject->name) . '_' . uniqid() . '.pdf';
                        Storage::disk('public')->put($destinationPath, File::get($file));

                        Exam::create([
                            'subject_id'    => $subject->id,
                            'created_by'    => $admin->id,
                            'title'         => ucwords($cleanTitle),
                            'academic_year' => '2025/2026',
                            'type'          => 'Esame Storico',
                            'file_path'     => $destinationPath,
                            'is_community'  => false,
                        ]);

                        $count++;
                        $this->info("✅ Importato: {$cleanTitle} -> {$subject->name}");
                    }
                }
                
                if (!$pdfFound) {
                    $this->warn("⚠️ Nessun file .pdf trovato nella cartella (e sottocartelle) di: {$dirName}");
                }
            } else {
                $this->error("❌ Cartella ignorata (Nessuna materia corrispondente nel DB): {$dirName}");
            }
        }

        if ($count > 0) {
            $this->info("🎉 Importazione completata! {$count} file aggiunti all'Archivio Storico.");
        } else {
            $this->warn("⚠️ Nessun file PDF è stato importato.");
        }
    }
}