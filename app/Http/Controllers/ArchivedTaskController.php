<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreArchivedTaskRequest;
use App\Models\ArchivedTask;
use App\Models\Task;
use Illuminate\Http\Request;
use App\Traits\HandleTaskCreation;

class ArchivedTaskController extends Controller
{
    use HandleTaskCreation;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $task = ArchivedTask::withTrashed()->get();

        return response()->json($task, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $task = ArchivedTask::findOrFail($id);

        return response()->json($task, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function permanently_delete(string $id)
    {
        $task = ArchivedTask::withTrashed()->where('id', $id)->firstOrFail();

        $task->forceDelete();

        return response()->json(['message' => 'Task permanently deleted'], 204);
    }

    public function restore(string $id)
    {
        $task = ArchivedTask::withTrashed()->where('id', $id)->firstOrFail();

        if(\App\Models\Task::find($task->id))
        {
            return response()->json(['message' => 'A status with this ID already exists.'], 409);
        }

        $data = [
            'id' => $task->id, 
            'task_definition' => $task->task_definition, 
            'status_id' => $task->status_id, 
            'user_id' => $task->user_id, 
            'created_at' => $task->created_at, 
            'updated_at' => $task->updated_at, 
            'deleted_at' => $task->deleted_at
        ];

        $this->createTask($data);
        
        $task->forceDelete();

        return response()->json(['message' => 'Task moved to recycle bin'], 201);
    }
}
