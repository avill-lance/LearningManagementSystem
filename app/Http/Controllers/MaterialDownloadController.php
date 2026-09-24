<?php

namespace App\Http\Controllers;

use App\Models\LearningMaterial;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MaterialDownloadController extends Controller
{
    /** Same disk TeacherMaterialController stores uploads on. */
    private const DISK = 'local';

    /**
     * Streams the file for a material a teacher owns, or a Published
     * material scoped to a student's active section — never a public URL,
     * so draft materials stay teacher-only.
     */
    public function show(Request $request, LearningMaterial $material): StreamedResponse
    {
        $this->authorizeAccess($request, $material);

        return Storage::disk(self::DISK)->download($material->file_url, $material->title);
    }

    /**
     * Same access rules as show(), but renders inline (Content-Disposition:
     * inline) so PDFs/videos display in the browser instead of downloading.
     */
    public function preview(Request $request, LearningMaterial $material): StreamedResponse
    {
        $this->authorizeAccess($request, $material);

        return Storage::disk(self::DISK)->response($material->file_url, $material->title);
    }

    /** Shared gate for both show() and preview() — one place to fix if the rules change. */
    private function authorizeAccess(Request $request, LearningMaterial $material): void
    {
        $role = $request->user()->role;

        if ($role === 'Teacher') {
            $teacher = Teacher::where('user_id', $request->user()->user_id)->firstOrFail();
            abort_unless($material->schedule?->teacher_id === $teacher->teacher_id, 403);
        } elseif ($role === 'Student') {
            $student = Student::where('user_id', $request->user()->user_id)->firstOrFail();
            $sectionId = $student->activeEnrollment?->section_id;

            abort_unless(
                $material->status === 'Published' && $sectionId !== null && $material->schedule?->section_id === $sectionId,
                403
            );
        } else {
            abort(403);
        }

        abort_unless(Storage::disk(self::DISK)->exists($material->file_url), 404);
    }
}
