<?php

namespace Database\Seeders;

use App\Models\Subject;
use App\Models\Exam;
use App\Models\User;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Primo Anno (I Anno)
        $primoAnno = [
            'Architettura degli elaboratori',
            'Calcolo delle probabilità e statistica',
            'Fisica',
            'Istituzioni di matematica',
            'Matematica discreta',
            'Programmazione',
            'Teoria',
        ];

        foreach ($primoAnno as $materia) {
            Subject::firstOrCreate([
                'name' => $materia,
                'year' => 'I',
                'description' => "Corso fondamentale del primo anno: {$materia}",
            ]);
        }

        // 2. Secondo Anno (II Anno)
        $secondoAnno = [
            'Algoritmi e strutture dati',
            'Basi di dati e laboratorio',
            'Calcolo numerico e laboratorio',
            'Economia e gestione aziendale',
        ];

        foreach ($secondoAnno as $materia) {
            Subject::firstOrCreate([
                'name' => $materia,
                'year' => 'II',
                'description' => "Corso del secondo anno: {$materia}",
            ]);
        }

        // 3. Terzo Anno (III Anno)
        $terzoAnno = [
            'Amministrazione e sicurezza',
            'Architettura e sviluppo Internet',
            'Ingegneria del software',
            'Inglese B2 (a scelta libera)',
            'Narrazione e robotica (opzionale)',
            'Tecnologie web',
            'Linguaggi di programmazione e laboratorio',
            'Reti di telecomunicazioni',
            'Sistemi operativi e laboratorio',
            'IA e Big Data',
        ];

        foreach ($terzoAnno as $materia) {
            Subject::firstOrCreate([
                'name' => $materia,
                'year' => 'III',
                'description' => "Corso del terzo anno: {$materia}",
            ]);
        }

        // 4. Inserimento di prove PDF iniziali (create dall'Admin Pierre Nzetem)
        $admin = User::where('role', 'admin')->first();
        $progSubject = Subject::where('name', 'Programmazione')->first();

        if ($admin && $progSubject) {
            Exam::firstOrCreate([
                'subject_id'    => $progSubject->id,
                'created_by'    => $admin->id,
                'title'         => 'Appello Scritto - Gennaio 2026',
                'academic_year' => '2025/2026',
                'type'          => 'Esame Scritto',
                'file_path'     => 'exams/esempio_programmazione.pdf',
            ]);
        }
    }
}