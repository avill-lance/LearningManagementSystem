<?php

namespace App\Http\Controllers;

use App\Models\Strand;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SubjectController extends Controller
{
    private const ALLOWED_ROLES = ['Admin', 'Staff', 'Registrar', 'Accounting'];

    public function index(Request $request): View
    {
        abort_unless(in_array($request->user()->role, self::ALLOWED_ROLES, true), 403);

        $query = Subject::query()->with(['strand', 'teacher.user']);

        // Search by subject code or name
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('subject_code', 'like', '%' . $request->search . '%')
                  ->orWhere('subject_name', 'like', '%' . $request->search . '%');
            });
        }

        foreach (['strand_id', 'teacher_id', 'subject_type', 'grade_level', 'semester', 'status'] as $filter) {
            if ($request->filled($filter)) {
                $query->where($filter, $request->input($filter));
            }
        }

        $subjects = $query->orderBy('grade_level')
                          ->orderBy('semester')
                          ->orderBy('subject_code')
                          ->paginate(10)
                          ->withQueryString();

        $stats = [
            'total' => Subject::count(),
            'active' => Subject::where('status', 'Active')->count(),
            'core' => Subject::where('subject_type', 'Core')->count(),
            'applied' => Subject::where('subject_type', 'Applied')->count(),
            'specialized' => Subject::where('subject_type', 'Specialized')->count(),
        ];

        $strands = Strand::orderBy('strand_name')->get(['strand_id', 'strand_code', 'strand_name']);

        $teachers = Teacher::with('user:user_id,first_name,last_name')
                           ->get(['teacher_id', 'user_id', 'teacher_number'])
                           ->sortBy(fn ($teacher) => $teacher->user?->last_name)
                           ->values();

        return view('admin.curriculum.index', compact('subjects', 'stats', 'strands', 'teachers'));
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless(in_array($request->user()->role, self::ALLOWED_ROLES, true), 403);

        Subject::create($this->validateSubject($request));

        return redirect()->route('admin.curriculum.index')->with('success', 'Subject created successfully.');
    }

    public function update(Request $request, $subject_id): RedirectResponse
    {
        abort_unless(in_array($request->user()->role, self::ALLOWED_ROLES, true), 403);

        $subject = Subject::findOrFail($subject_id);
        $subject->update($this->validateSubject($request, $subject->subject_id));

        return redirect()->back()->with('success', 'Subject updated successfully.');
    }

    public function destroy(Request $request, $subject_id): RedirectResponse
    {
        abort_unless(in_array($request->user()->role, self::ALLOWED_ROLES, true), 403);

        $subject = Subject::findOrFail($subject_id);

        try {
            $subject->delete();
        } catch (QueryException $e) {
            // Schedules, grades and grade components reference subjects without cascading deletes.
            return redirect()->back()->with('error', 'Subject "' . $subject->subject_code . '" is already used by schedules or grades. Set it to Inactive instead.');
        }

        return redirect()->back()->with('success', 'Subject deleted successfully.');
    }

    private function validateSubject(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'strand_id' => 'nullable|integer|exists:strands,strand_id',
            'teacher_id' => 'nullable|integer|exists:teachers,teacher_id',
            'subject_code' => ['required', 'string', 'max:20', Rule::unique('subjects', 'subject_code')->ignore($ignoreId, 'subject_id')],
            'subject_name' => 'required|string|max:150',
            'subject_type' => 'required|in:Core,Applied,Specialized',
            'grade_level' => 'required|in:11,12',
            'semester' => 'required|in:1st Semester,2nd Semester',
            'units' => 'required|numeric|min:0|max:99.9',
            'description' => 'nullable|string',
            'status' => 'required|in:Active,Inactive',
        ]);
    }
}
