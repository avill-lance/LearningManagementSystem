<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Services\Dashboard\TeacherDashboardService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TeacherDashboardController extends Controller
{
    public function __construct(private readonly TeacherDashboardService $dashboard)
    {
    }

    public function index(Request $request): View
    {
        abort_unless($request->user()->role === 'Teacher', 403);

        $teacher = Teacher::where('user_id', $request->user()->user_id)->firstOrFail();

        return view('teacher.dashboard', $this->dashboard->summaryFor($teacher));
    }
}
