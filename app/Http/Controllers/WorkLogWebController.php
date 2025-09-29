<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWorkLogRequest;
use App\Http\Requests\UpdateWorkLogRequest;
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
        $projects = Project::where('status', 1)->get();
        return view('work_logs.create', compact('projects'));
    }

    // Store new work log
    public function store(StoreWorkLogRequest  $request)
    {
        try {

            $totalMinutes = 0;
           
            foreach ($request->tasks as $task) {
                [$h, $m] = explode(':', $task['log_hours']);
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
                    'log_hours' => $task['log_hours']
                ]);
            }

            DB::commit();

            return redirect()->route('work-log.index')->with('success', 'Work log submitted successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['message' => 'Failed to create work log', 'error' => $e->getMessage()])->withInput();
        }
    }

    // Show form to edit work log
    public function edit($id)
    {
        $entry = WorkLogUserEntry::findOrFail($id);
        $this->authorizeEntry($entry);
        $projects = Project::where('status', 1)->get();
        $entry->load('tasks');
        return view('work_logs.edit', compact('entry', 'projects'));
    } 
    
    // Update work log
    public function update(UpdateWorkLogRequest  $request, $id)
    {
        $entry = WorkLogUserEntry::findOrFail($id);
        $this->authorizeEntry($entry);

        try {
            $totalMinutes = 0;
           
            foreach ($request->tasks as $task) {
                [$h, $m] = explode(':', $task['log_hours']);
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

            // Update parent entry
            $entry->update([
                'date' => $request->date,
            ]);

            // Delete existing tasks
            WorkLogUserTask::where('work_log_user_entry_id', $entry->id)->delete();

            //dd($request->tasks);

            // Create new tasks
            foreach ($request->tasks as $task) {
                WorkLogUserTask::create([
                    'work_log_user_entry_id' => $entry->id,
                    'project_id' => $task['project_id'],
                    'task_description' => $task['task_description'],
                    'log_hours' => $task['log_hours']
                ]);
            }

            DB::commit();

            return redirect()->route('work-log.index')->with('success', 'Work log updated successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['message' => 'Failed to update work log', 'error' => $e->getMessage()])->withInput();
        }
    }


    // Delete work log
    public function destroy($id)
    {
        $entry = WorkLogUserEntry::findOrFail($id);
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
