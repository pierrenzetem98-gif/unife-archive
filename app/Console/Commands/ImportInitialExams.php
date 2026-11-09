<?php

namespace App\Console\Commands;

use App\Models\Exam;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Http\File as HttpFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImportInitialExams extends Command
{
    protected $signature = 'exams:import
        {--email= : Email dell\'admin autore (default: primo admin)}
        {--year=2025/2026 : Anno accademico da assegnare (es. 2023/2024)}
        {--dry-run : Mostra cosa verrebbe importato senza scrivere nulla}';

    protected $description = 'Importa i PDF da import_exams/ (sottocartelle incluse), senza duplicati.';

    public function handle(): int
    {
        // 1. Admin autore
        $admin = $this->option('email')
            ? User::where('email', $this->option('email'))->first()
            : User::where('role', 'admin')->orderBy('id')->first();

        if (! $admin || ! $admin->isAdmin()) {
            $this->error('❌ Nessun admin valido trovato. Usa --email=...');
            return self::FAILURE;
        }

        // 2. Anno accademico
        $year = (string) $this->option('year');
        if (! preg_match('/^\d{4}\/\d{4}$/', $year)) {
            $this->error("❌ Anno non valido: {$year} (formato atteso: 2024/2025)");
            return self::FAILURE;
        }

        // 3. Cartella sorgente
        $sourceDir = base_path('import_exams');
        if (! File::isDirectory($sourceDir)) {
            $this->error("❌ La cartella {$sourceDir} non esiste.");
            return self::FAILURE;
        }

        $directories = File::directories($sourceDir);
        if (empty($directories)) {
            $this->error("❌ Non ci sono sottocartelle in 'import_exams'.");
            return self::FAILURE;
        }

        $dryRun = (bool) $this->option('dry-run');
        if ($dryRun) {
            $this->warn('🔎 DRY-RUN: nessun file o record verrà creato.');
        }

        // Materie normalizzate, dalla più lunga alla più corta
        $subjects = Subject::all()
            ->map(fn ($s) => ['model' => $s, 'key' => self::normalize($s->name)])
            ->filter(fn ($s) => $s['key'] !== '')
            ->sortByDesc(fn ($s) => strlen($s['key']))
            ->values();

        $imported = $skipped = $failed = 0;

        foreach ($directories as $dir) {
            $dirName = basename($dir);

            // Rimuove il prefisso dell'anno ("I - ", "II - ", "III – ")
            $folderKey = self::normalize(preg_replace('/^\s*I{1,3}\s*[-–]\s*/u', '', $dirName));

            // Corrispondenza esatta prima, poi la più lunga contenuta
            $match = $subjects->first(fn ($s) => $s['key'] === $folderKey)
                ?? $subjects->first(fn ($s) => str_contains($folderKey, $s['key']));

            if (! $match) {
                $this->error("❌ Cartella ignorata (nessuna materia corrispondente): {$dirName}");
                continue;
            }

            $subject = $match['model'];
            $pdfFound = false;

            foreach (File::allFiles($dir) as $file) {
                if (strtolower($file->getExtension()) !== 'pdf') {
                    continue;
                }
                $pdfFound = true;

                // Verifica che sia davvero un PDF
                $head = @file_get_contents($file->getPathname(), false, null, 0, 5);
                if ($head !== '%PDF-') {
                    $this->warn("⚠️ Non è un PDF valido, ignorato: {$file->getRelativePathname()}");
                    $failed++;
                    continue;
                }

                $title = ucwords(trim(preg_replace('/[\s_]+/', ' ', $file->getFilenameWithoutExtension())));

                // Niente duplicati
                $exists = Exam::where('subject_id', $subject->id)
                    ->where('title', $title)
                    ->exists();

                if ($exists) {
                    $this->line("⏭️  Già presente: {$title} -> {$subject->name}");
                    $skipped++;
                    continue;
                }

                if ($dryRun) {
                    $this->info("➕ Da importare: {$title} -> {$subject->name}");
                    $imported++;
                    continue;
                }

                $storedPath = null;

                try {
                    $storedPath = Storage::disk('public')->putFileAs(
                        'exams',
                        new HttpFile($file->getPathname()),
                        Str::slug($subject->name) . '_' . Str::random(12) . '.pdf'
                    );

                    Exam::create([
                        'subject_id'    => $subject->id,
                        'created_by'    => $admin->id,
                        'title'         => $title,
                        'academic_year' => $year,
                        'type'          => 'Esame Storico',
                        'file_path'     => $storedPath,
                        'is_community'  => false,
                    ]);

                    $imported++;
                    $this->info("✅ Importato: {$title} -> {$subject->name}");
                } catch (\Throwable $e) {
                    if ($storedPath) {
                        Storage::disk('public')->delete($storedPath);
                    }
                    $failed++;
                    $this->error("❌ Errore su {$file->getFilename()}: {$e->getMessage()}");
                }
            }

            if (! $pdfFound) {
                $this->warn("⚠️ Nessun file .pdf trovato in: {$dirName}");
            }
        }

        $this->newLine();
        $this->table(
            ['Importati', 'Già presenti', 'Errori'],
            [[$imported, $skipped, $failed]]
        );

        return $failed > 0 ? self::FAILURE : self::SUCCESS;
    }

    private static function normalize(string $value): string
    {
        return strtolower(preg_replace('/[^a-zA-Z0-9]/', '', Str::ascii($value)));
    }
}