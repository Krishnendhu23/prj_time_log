<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLeaveRequest;
use Illuminate\Http\Request;
use App\Models\Leave;
use Illuminate\Support\Facades\Auth;

class LeaveController extends Controller
{

    public function index()
    {
        $leaves = Leave::where('user_id', Auth::id())->latest()->get();
        return view('leave.index', compact('leaves'));
    }

    public function create()
    {
        return view('leave.create');
    }

    public function store(StoreLeaveRequest $request)
    {
        Leave::create([
            'user_id' => Auth::id(),
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'reason' => $request->reason,
        ]);
        return redirect()->route('leave.index')->with('success', 'Leave request submitted successfully.');
    }

    public function show($id)
    {
        // Display the specified leave entry
    }

    public function edit($id)
    {
        // Show the form for editing the specified leave entry
    }

    public function update(Request $request, $id)
    {
        // Update the specified leave entry in storage
    }

    public function destroy($id)
    {
        $leave = Leave::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $leave->delete();
        return redirect()->route('leave.index')->with('success', 'Leave request deleted successfully.');
    }
}
