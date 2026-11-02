<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display listing with filters / search / sort.
     */
    public function index(Request $request)
    {
        $query = Task::query();

        // Search by title
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Filter: active / completed
        if ($request->filter === 'active') {
            $query->where('completed', false);
        } elseif ($request->filter === 'completed') {
            $query->where('completed', true);
        }

        // Sort
        if ($request->sort === 'priority') {
            $query->orderByRaw("CASE priority WHEN 'high' THEN 1 WHEN 'medium' THEN 2 WHEN 'low' THEN 3 ELSE 4 END");
        } else {
            // default: deadline (nulls last)
            $query->orderByRaw('CASE WHEN deadline IS NULL THEN 1 ELSE 0 END, deadline ASC');
        }

        $tasks = $query->get();

        return view('tasks.index', compact('tasks'));
    }

    /**
     * Store a new task.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'deadline'    => 'nullable|date',
            'priority'    => 'required|in:low,medium,high',
        ]);

        $validated['completed'] = false;

        Task::create($validated);

        return redirect()->route('tasks.index')->with('success', 'Tugas berhasil ditambahkan!');
    }

    /**
     * Update an existing task.
     */
    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'deadline'    => 'nullable|date',
            'priority'    => 'required|in:low,medium,high',
        ]);

        $task->update($validated);

        return redirect()->route('tasks.index')->with('success', 'Tugas berhasil diperbarui!');
    }

    /**
     * Toggle completed status.
     */
    public function toggle(Task $task)
    {
        $task->update(['completed' => !$task->completed]);

        return redirect()->route('tasks.index');
    }

    /**
     * Delete a task.
     */
    public function destroy(Task $task)
    {
        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Tugas berhasil dihapus!');
    }
}
