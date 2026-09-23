<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Services\Dashboard\StudentDashboardService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentDashboardController extends Controller
{
    public function __construct(private readonly StudentDashboardService $dashboard)
    {
    }

    public function index(Request $request): View
    {
        abort_unless($request->user()->role === 'Student', 403);

        $student = Student::where('user_id', $request->user()->user_id)->firstOrFail();

        return view('student.dashboard', $this->dashboard->summaryFor($student));
    }
}
