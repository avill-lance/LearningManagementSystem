<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCalendarEventRequest;
use App\Models\ScheduleEvent;
use App\Models\Student;
use App\Services\Calendar\CalendarEventService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class CalendarController extends Controller
{
    public function __construct(private readonly CalendarEventService $calendar)
    {
    }

    public function index(Request $request): View
    {
        abort_unless($request->user()->role === 'Student', 403);

        return view('shared.calendar.index');
    }

    /**
     * JSON feed consumed by the FullCalendar widget. Accepts FullCalendar's
     * `start`/`end` range query params (ISO datetimes).
     */
    public function events(Request $request): JsonResponse
    {
        abort_unless($request->user()->role === 'Student', 403);

        $student = Student::where('user_id', $request->user()->user_id)->firstOrFail();

        $from = Carbon::parse($request->query('start', now()->startOfMonth()));
        $to = Carbon::parse($request->query('end', now()->endOfMonth()));

        return response()->json($this->calendar->feedFor($student, $from, $to));
    }

    public function store(StoreCalendarEventRequest $request): JsonResponse
    {
        $student = Student::where('user_id', $request->user()->user_id)->firstOrFail();

        $event = ScheduleEvent::create([
            ...$request->validated(),
            'created_by_role' => 'Student',
            'created_by_id' => $student->student_id,
            'event_type' => 'Personal',
            'status' => 'Scheduled',
        ]);

        return response()->json($event, 201);
    }

    public function update(StoreCalendarEventRequest $request, ScheduleEvent $event): JsonResponse
    {
        $this->authorizePersonalEvent($request, $event);

        $event->update($request->validated());

        return response()->json($event);
    }

    public function destroy(Request $request, ScheduleEvent $event): JsonResponse
    {
        abort_unless($request->user()->role === 'Student', 403);
        $this->authorizePersonalEvent($request, $event);

        $event->delete();

        return response()->json(['ok' => true]);
    }

    /** Students may only edit/delete their own personal events. */
    private function authorizePersonalEvent(Request $request, ScheduleEvent $event): void
    {
        $student = Student::where('user_id', $request->user()->user_id)->firstOrFail();

        abort_unless(
            $event->created_by_role === 'Student' && $event->created_by_id === $student->student_id,
            403
        );
    }
}
