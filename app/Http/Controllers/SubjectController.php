<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index(Request $request)
    {
        $selectedYear = $request->query('year', 'I');

        $subjects = Subject::where('year', $selectedYear)
            ->withCount('exams')
            ->get();

        return view('subjects.index', compact('subjects', 'selectedYear'));
    }

    public function show(Subject $subject)
    {
        $subject->load(['exams.uploader']);

        // Matières de la même année pour le menu de saut direct
        $siblingSubjects = Subject::where('year', $subject->year)
            ->where('id', '!=', $subject->id)
            ->orderBy('name')
            ->get();

        return view('subjects.show', compact('subject', 'siblingSubjects'));
    }
}