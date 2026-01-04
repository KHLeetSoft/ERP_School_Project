<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\TeacherClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!Auth::check()) {
                return redirect()->route('teacher.login')->with('error', 'Please login to access this page.');
            }

            $user = Auth::user();
            if (!$user->userRole || $user->userRole->name !== 'Teacher') {
                Auth::logout();
                return redirect()->route('teacher.login')->with('error', 'Access denied. Teacher role required.');
            }

            if (!$user->status) {
                Auth::logout();
                return redirect()->route('teacher.login')->with('error', 'Your account is inactive. Please contact administrator.');
            }

            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $teacherId = Auth::id();
        $teacherClasses = TeacherClass::where('teacher_id', $teacherId)->get();
        
        // Get current week's schedule
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        
        $schedules = Schedule::with(['teacher'])
            ->byTeacher($teacherId)
            ->active()
            ->current()
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get()
            ->groupBy('day_of_week');

        $scheduleStats = [
            'total_periods' => Schedule::byTeacher($teacherId)->active()->count(),
            'this_week' => Schedule::byTeacher($teacherId)->active()->current()->count(),
            'today_periods' => Schedule::byTeacher($teacherId)->active()->current()->byDay(strtolower(now()->format('l')))->count(),
            'next_class' => $this->getNextClass($teacherId),
        ];

        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        
        return view('teacher.schedule.index', compact('schedules', 'teacherClasses', 'scheduleStats', 'days', 'startOfWeek', 'endOfWeek'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $teacherId = Auth::id();
        $teacherClasses = TeacherClass::where('teacher_id', $teacherId)->get();
        
        return view('teacher.schedule.create', compact('teacherClasses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'class_name' => 'required|string|max:255',
            'subject_name' => 'required|string|max:255',
            'room_number' => 'nullable|string|max:50',
            'day_of_week' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'schedule_type' => 'required|in:regular,substitute,special',
            'description' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:500',
            'effective_from' => 'nullable|date',
            'effective_until' => 'nullable|date|after:effective_from',
        ]);

        $teacherId = Auth::id();

        // Check for time conflicts
        $conflict = Schedule::byTeacher($teacherId)
            ->byDay($request->day_of_week)
            ->where(function($query) use ($request) {
                $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                      ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                      ->orWhere(function($q) use ($request) {
                          $q->where('start_time', '<=', $request->start_time)
                            ->where('end_time', '>=', $request->end_time);
                      });
            })
            ->exists();

        if ($conflict) {
            return redirect()->back()
                ->with('error', 'Time conflict detected. Please choose a different time slot.')
                ->withInput();
        }

        Schedule::create([
            'teacher_id' => $teacherId,
            'class_name' => $request->class_name,
            'subject_name' => $request->subject_name,
            'room_number' => $request->room_number,
            'day_of_week' => $request->day_of_week,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'schedule_type' => $request->schedule_type,
            'description' => $request->description,
            'notes' => $request->notes,
            'effective_from' => $request->effective_from,
            'effective_until' => $request->effective_until,
        ]);

        return redirect()->route('teacher.schedule.index')
            ->with('success', 'Schedule created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Schedule $schedule)
    {
        $schedule->load(['teacher']);
        
        return view('teacher.schedule.show', compact('schedule'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Schedule $schedule)
    {
        $teacherId = Auth::id();
        $teacherClasses = TeacherClass::where('teacher_id', $teacherId)->get();
        
        return view('teacher.schedule.edit', compact('schedule', 'teacherClasses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Schedule $schedule)
    {
        $request->validate([
            'class_name' => 'required|string|max:255',
            'subject_name' => 'required|string|max:255',
            'room_number' => 'nullable|string|max:50',
            'day_of_week' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'schedule_type' => 'required|in:regular,substitute,special',
            'description' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:500',
            'effective_from' => 'nullable|date',
            'effective_until' => 'nullable|date|after:effective_from',
            'is_active' => 'boolean',
        ]);

        // Check for time conflicts (excluding current schedule)
        $conflict = Schedule::byTeacher($schedule->teacher_id)
            ->where('id', '!=', $schedule->id)
            ->byDay($request->day_of_week)
            ->where(function($query) use ($request) {
                $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                      ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                      ->orWhere(function($q) use ($request) {
                          $q->where('start_time', '<=', $request->start_time)
                            ->where('end_time', '>=', $request->end_time);
                      });
            })
            ->exists();

        if ($conflict) {
            return redirect()->back()
                ->with('error', 'Time conflict detected. Please choose a different time slot.')
                ->withInput();
        }

        $schedule->update([
            'class_name' => $request->class_name,
            'subject_name' => $request->subject_name,
            'room_number' => $request->room_number,
            'day_of_week' => $request->day_of_week,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'schedule_type' => $request->schedule_type,
            'description' => $request->description,
            'notes' => $request->notes,
            'effective_from' => $request->effective_from,
            'effective_until' => $request->effective_until,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('teacher.schedule.index')
            ->with('success', 'Schedule updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Schedule $schedule)
    {
        $schedule->delete();

        return redirect()->route('teacher.schedule.index')
            ->with('success', 'Schedule deleted successfully!');
    }

    /**
     * Get today's schedule
     */
    public function today()
    {
        $teacherId = Auth::id();
        $today = strtolower(now()->format('l'));
        
        $schedules = Schedule::with(['teacher'])
            ->byTeacher($teacherId)
            ->byDay($today)
            ->active()
            ->current()
            ->orderBy('start_time')
            ->get();

        return view('teacher.schedule.today', compact('schedules'));
    }

    /**
     * Get weekly schedule
     */
    public function weekly()
    {
        $teacherId = Auth::id();
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        
        $schedules = Schedule::with(['teacher'])
            ->byTeacher($teacherId)
            ->active()
            ->current()
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get()
            ->groupBy('day_of_week');

        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        
        return view('teacher.schedule.weekly', compact('schedules', 'days', 'startOfWeek', 'endOfWeek'));
    }

    /**
     * Get next class
     */
    private function getNextClass($teacherId)
    {
        $today = strtolower(now()->format('l'));
        $currentTime = now()->format('H:i:s');
        
        $nextClass = Schedule::byTeacher($teacherId)
            ->active()
            ->current()
            ->where(function($query) use ($today, $currentTime) {
                $query->where('day_of_week', $today)
                      ->where('start_time', '>', $currentTime)
                      ->orWhere('day_of_week', '>', $today);
            })
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->first();

        return $nextClass;
    }
}
