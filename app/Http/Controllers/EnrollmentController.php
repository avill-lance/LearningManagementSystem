<?php

namespace App\Http\Controllers;

use App\Models\ClassSection;
use App\Models\Enrollment;
use App\Models\SchoolYear;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class EnrollmentController extends Controller
{
    private const ALLOWED_ROLES = ['Admin', 'Staff', 'Registrar'];

    public function index(Request $request): View
    {
        abort_unless(in_array($request->user()->role, self::ALLOWED_ROLES, true), 403);

        $query = Enrollment::query()->with(['student.user', 'section.strand']);

        // Search by student name, student number or LRN
        if ($request->filled('search')) {
            $search = '%' . $request->search . '%';
            $query->whereHas('student', function ($q) use ($search) {
                $q->where('student_number', 'like', $search)
                  ->orWhere('lrn', 'like', $search)
                  ->orWhereHas('user', function ($u) use ($search) {
                      $u->where('first_name', 'like', $search)
                        ->orWhere('last_name', 'like', $search);
                  });
            });
        }

        foreach (['school_year_id', 'section_id', 'semester', 'status'] as $filter) {
            if ($request->filled($filter)) {
                $query->where($filter, $request->input($filter));
            }
        }

        $enrollments = $query->orderByDesc('date_enrolled')
                             ->orderByDesc('enrollment_id')
                             ->paginate(10)
                             ->withQueryString();

        $stats = [
            'total' => Enrollment::count(),
            'enrolled' => Enrollment::where('status', 'Enrolled')->count(),
            'pending' => Enrollment::where('status', 'Pending')->count(),
            'dropped' => Enrollment::where('status', 'Dropped')->count(),
        ];

        $schoolYears = SchoolYear::orderByDesc('year')->get(['school_year_id', 'year', 'status']);

        $sections = ClassSection::with('strand:strand_id,strand_code')
                                ->withCount(['enrollments as enrolled_count' => fn ($q) => $q->where('status', 'Enrolled')])
                                ->orderBy('grade_level')
                                ->orderBy('section_name')
                                ->get();

        $students = Student::with('user:user_id,first_name,last_name')
                           ->get(['student_id', 'user_id', 'student_number', 'grade_level'])
                           ->sortBy(fn ($student) => $student->user?->last_name)
                           ->values();

        return view('admin.enrollment.index', compact('enrollments', 'stats', 'schoolYears', 'sections', 'students'));
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless(in_array($request->user()->role, self::ALLOWED_ROLES, true), 403);

        Enrollment::create($this->validateEnrollment($request));

        return redirect()->route('admin.enrollment.index')->with('success', 'Student enrolled successfully.');
    }

    public function update(Request $request, $enrollment_id): RedirectResponse
    {
        abort_unless(in_array($request->user()->role, self::ALLOWED_ROLES, true), 403);

        $enrollment = Enrollment::findOrFail($enrollment_id);
        $enrollment->update($this->validateEnrollment($request, $enrollment->enrollment_id));

        return redirect()->back()->with('success', 'Enrollment updated successfully.');
    }

    public function destroy(Request $request, $enrollment_id): RedirectResponse
    {
        abort_unless(in_array($request->user()->role, self::ALLOWED_ROLES, true), 403);

        Enrollment::findOrFail($enrollment_id)->delete();

        return redirect()->back()->with('success', 'Enrollment record deleted successfully.');
    }

    private function validateEnrollment(Request $request, ?int $ignoreId = null): array
    {
        $data = $request->validate([
            'student_id' => [
                'required', 'integer', 'exists:students,student_id',
                // One enrollment per student per school year and semester
                Rule::unique('enrollments', 'student_id')
                    ->where('school_year_id', $request->input('school_year_id'))
                    ->where('semester', $request->input('semester'))
                    ->ignore($ignoreId, 'enrollment_id'),
            ],
            'section_id' => 'required|integer|exists:class_sections,section_id',
            'school_year_id' => 'required|integer|exists:school_years,school_year_id',
            'semester' => 'required|in:1st Semester,2nd Semester',
            'status' => 'required|in:Enrolled,Pending,Dropped',
        ], [
            'student_id.unique' => 'This student already has an enrollment for the selected school year and semester.',
        ]);

        // Keep the section within its slot limit when counting enrolled students
        if ($data['status'] === 'Enrolled') {
            $section = ClassSection::findOrFail($data['section_id']);
            $taken = Enrollment::where('section_id', $section->section_id)
                               ->where('status', 'Enrolled')
                               ->when($ignoreId, fn ($q) => $q->where('enrollment_id', '!=', $ignoreId))
                               ->count();

            if ($taken >= $section->max_slots) {
                throw ValidationException::withMessages([
                    'section_id' => 'Section "' . $section->section_name . '" is full (' . $section->max_slots . ' slots).',
                ]);
            }
        }

        // The table keeps a denormalized copy of the school year label
        $data['school_year'] = SchoolYear::findOrFail($data['school_year_id'])->year;

        return $data;
    }
}
