<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWorkLogRequest;
use App\Models\Project;
use App\Models\WorkLogUserEntry;
use App\Models\WorkLogUserTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WorkLogWebController extends Controller
{

    public function index()
    {

        $entries = WorkLogUserEntry::with(['tasks','tasks.project'])
            ->where('user_id', Auth::id())
            ->orderByDesc('date')
            ->get();
        return view('work_logs.index', compact('entries'));
    }

    // Show form to create work log
    public function create()
    {
        $projects = Project::all();
        return view('work_logs.create', compact('projects'));
    }

    // Store new work log
    public function store(StoreWorkLogRequest  $request)
    {
        try {

            $totalMinutes = 0;
           
            foreach ($request->tasks as $task) {
                [$h, $m] = explode(':', $task['hours_minutes']);
                $minutes = ((int)$h * 60) + (int)$m;

                if ($minutes > 600) { // max 10 hours per task
                    return back()->withErrors(['tasks' => 'Each task cannot exceed 10 hours'])->withInput();
                }

                $totalMinutes += $minutes;
            }
            
            if ($totalMinutes > 600) {
                return back()->withErrors(['tasks' => 'Total work hours for the day cannot exceed 10 hours'])->withInput();
            }

            DB::beginTransaction();

            // Create parent entry
            $entry = WorkLogUserEntry::create([
                'user_id' => Auth::id(),
                'date' => $request->date,
            ]);

            // Create tasks
            foreach ($request->tasks as $task) {
                WorkLogUserTask::create([
                    'work_log_user_entry_id' => $entry->id,
                    'project_id' => $task['project_id'],
                    'task_description' => $task['task_description'],
                    'log_hours' => $task['hours_minutes']
                ]);
            }

            DB::commit();

            return redirect()->route('work-log.index')->with('success', 'Work log submitted successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['message' => 'Failed to create work log', 'error' => $e->getMessage()])->withInput();
        }
    }


    // Delete work log
    public function destroy(WorkLogUserEntry $entry)
    {
        $this->authorizeEntry($entry);
        $entry->delete();
        return redirect()->route('work-log.index')->with('success', 'Work log deleted successfully.');
    }

    // Ensure user can only manage their own entries
    private function authorizeEntry(WorkLogUserEntry $entry)
    {
        if ($entry->user_id !== Auth::id()) {
            abort(403);
        }
    }
}
