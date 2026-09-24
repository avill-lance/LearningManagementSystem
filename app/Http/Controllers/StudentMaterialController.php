<?php

namespace App\Http\Controllers;

use App\Models\LearningMaterial;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentMaterialController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless($request->user()->role === 'Student', 403);

        $student = Student::where('user_id', $request->user()->user_id)->firstOrFail();
        $sectionId = $student->activeEnrollment?->section_id;

        $materials = $sectionId === null
            ? collect()
            : LearningMaterial::visibleToSection($sectionId)
                ->with(['schedule.subject'])
                ->orderByDesc('uploaded_at')
                ->get();

        $materialsBySubject = $materials->groupBy(fn (LearningMaterial $material) => $material->schedule?->subject?->subject_name ?? 'Other');

        return view('student.materials.index', compact('materialsBySubject'));
    }
}
