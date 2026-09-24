<?php

namespace App\Http\Controllers;

use App\Models\LearningMaterial;
use App\Models\Schedule;
use App\Models\Teacher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class TeacherMaterialController extends Controller
{
    /** Uploaded files are kept off the public disk — draft materials must never get a guessable URL. */
    private const DISK = 'local';

    public function index(Request $request): View
    {
        $teacher = $this->authorizedTeacher($request);

        $query = LearningMaterial::forTeacher($teacher->teacher_id)->with(['schedule.subject', 'schedule.section']);

        if ($request->filled('schedule_id')) {
            $query->where('schedule_id', $request->input('schedule_id'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $materials = $query->orderByDesc('uploaded_at')->paginate(10)->withQueryString();

        $stats = [
            'total' => LearningMaterial::forTeacher($teacher->teacher_id)->count(),
            'draft' => LearningMaterial::forTeacher($teacher->teacher_id)->where('status', 'Draft')->count(),
            'published' => LearningMaterial::forTeacher($teacher->teacher_id)->where('status', 'Published')->count(),
            'archived' => LearningMaterial::forTeacher($teacher->teacher_id)->where('status', 'Archived')->count(),
        ];

        $schedules = Schedule::where('teacher_id', $teacher->teacher_id)
            ->with(['subject', 'section'])
            ->get();

        return view('teacher.materials.index', compact('materials', 'stats', 'schedules'));
    }

    public function store(Request $request): RedirectResponse
    {
        $teacher = $this->authorizedTeacher($request);

        $data = $this->validateMaterial($request, $teacher->teacher_id);

        $path = $request->file('file')->store('learning_materials/' . $data['schedule_id'], self::DISK);

        LearningMaterial::create([
            'schedule_id' => $data['schedule_id'],
            'title' => $data['title'],
            'status' => $data['status'],
            'file_url' => $path,
            'uploaded_by' => $request->user()->user_id,
        ]);

        return redirect()->route('teacher.materials.index')->with('success', 'Material uploaded successfully.');
    }

    public function update(Request $request, LearningMaterial $material): RedirectResponse
    {
        $teacher = $this->authorizedTeacher($request);
        $this->authorizeOwnership($material, $teacher->teacher_id);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'status' => ['required', 'in:Draft,Published,Archived'],
            'file' => ['nullable', 'file', 'max:20480', 'mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,mp4,zip'],
        ]);

        if ($request->hasFile('file')) {
            Storage::disk(self::DISK)->delete($material->file_url);
            $data['file_url'] = $request->file('file')->store('learning_materials/' . $material->schedule_id, self::DISK);
        }

        $material->update($data);

        return redirect()->route('teacher.materials.index')->with('success', 'Material updated successfully.');
    }

    public function destroy(Request $request, LearningMaterial $material): RedirectResponse
    {
        $teacher = $this->authorizedTeacher($request);
        $this->authorizeOwnership($material, $teacher->teacher_id);

        Storage::disk(self::DISK)->delete($material->file_url);
        $material->delete();

        return redirect()->route('teacher.materials.index')->with('success', 'Material deleted successfully.');
    }

    private function authorizedTeacher(Request $request): Teacher
    {
        abort_unless($request->user()->role === 'Teacher', 403);

        return Teacher::where('user_id', $request->user()->user_id)->firstOrFail();
    }

    /** A teacher may only manage materials on their own schedules. */
    private function authorizeOwnership(LearningMaterial $material, int $teacherId): void
    {
        abort_unless($material->schedule?->teacher_id === $teacherId, 403);
    }

    private function validateMaterial(Request $request, int $teacherId): array
    {
        $data = $request->validate([
            'schedule_id' => ['required', 'integer'],
            'title' => ['required', 'string', 'max:255'],
            'status' => ['required', 'in:Draft,Published,Archived'],
            'file' => ['required', 'file', 'max:20480', 'mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,mp4,zip'],
        ]);

        // exists:schedules,schedule_id isn't enough — the schedule must be this teacher's own.
        $owns = Schedule::where('schedule_id', $data['schedule_id'])->where('teacher_id', $teacherId)->exists();
        abort_unless($owns, 403);

        return $data;
    }
}
