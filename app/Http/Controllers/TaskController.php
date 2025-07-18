<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Traits\HandleTaskCreation;

class TaskController extends Controller
{
    use HandleTaskCreation;
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $tasks = Task::all();

        return response()->json($tasks, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request): JsonResponse
    {
        $status = Task::create($request->validated());

        return response()->json($status, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $task = Task::findOrFail($id);

        return response()->json($task, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, string $id): JsonResponse
    {
        $task = Task::findOrFail($id);

        $task->update($request->validated());

        return response()->json($task, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $task = Task::findOrFail($id);

        $task->delete();

        return response()->json(['message' => 'Status deleted successfully'], 204);
    }

    public function trashed(): JsonResponse
    {
        $status = ArchivedStatus::onlyTrashed()->get();

        return response()->json($status, 200);
    }

    public function restore(string $id): JsonResponse
    {
        $deleted_task = Task::withTrashed()->where('id', $id)->firstOrFail();

        $deleted_task->restore();

        return response()->json($deleted_task, 200);
    }

    public function permanently_delete(string $id): JsonResponse
    {
        $deleted_task = Task::onlyTrashed()->where('id', $id)->firstOrFail();

        $data = [
            'id' => $deleted_task->id,
            'task_title' => $deleted_task->task_title,
            'task_definition' => $deleted_task->task_definition,
            'status_id' => $deleted_task->status_id,
            'user_id' => $deleted_task->user_id,
            'created_at' => $deleted_task->created_at,
            'updated_at' => $deleted_task->updated_at,
            'deleted_at' => $deleted_task->deleted_at,
        ];

        $this->createArchivedTask($data);

        $deleted_task->forceDelete();

        return response()->json(['message' => 'Status permanently deleted'], 204);
    }
}
