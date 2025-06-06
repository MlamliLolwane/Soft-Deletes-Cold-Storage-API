<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreArchivedStatus;
use App\Models\ArchivedStatus;
use App\Traits\HandlesStatusCreation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ArchivedStatusController extends Controller
{
    use HandlesStatusCreation;
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $status = ArchivedStatus::withTrashed()->get();

        return response()->json($status, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $archived_status = ArchivedStatus::withTrashed()->where('id', $id)->firstOrFail();


        return response()->json($archived_status, 200);
    }

    public function restore(string $id): JsonResponse
    {
        $archived_status = ArchivedStatus::withTrashed()->where('id', $id)->firstOrFail();

        // Check if the ID is already used in statuses table
        if (\App\Models\Status::find($archived_status->id)) {
            return response()->json(['message' => 'A status with this ID already exists.'], 409);
        }

        //Insert archived status details into status table
        $data = [
            'id' => $archived_status->id,
            'status_code' => $archived_status->status_code,
            'created_at' => $archived_status->created_at,
            'updated_at' => $archived_status->updated_at,
            'deleted_at' => $archived_status->deleted_at
        ];

        $this->createStatus($data);
        $archived_status->forceDelete();

        return response()->json(['message' => 'Status restored successfully'], 201);
    }

    public function permanently_delete(string $id): JsonResponse
    {
        $status = ArchivedStatus::withTrashed()->where('id', $id)->firstOrFail();

        $status->forceDelete();

        return response()->json(['message' => 'Status permanently deleted'], 204);
    }
}
