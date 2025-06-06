<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateStatusRequest;
use App\Http\Requests\UpdateStatusRequest;
use App\Models\Status;
use App\Traits\HandlesStatusCreation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    use HandlesStatusCreation;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $statuses = Status::all();

        return response()->json($statuses, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateStatusRequest $request)
    {
        $status = $this->createStatus($request->validated());

        return response()->json($status, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $status = Status::findOrFail($id);

        return response()->json($status);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStatusRequest $request, string $id)
    {
        $status = Status::findOrFail($id);

        $status->update($request->validated());

        return response()->json($status, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $status = Status::findOrFail($id);

        $status->delete();

        return response('Status deleted successfully', 204);
    }

    //Restore from bin to main records
    public function restore(string $id)
    {
        $status = Status::onlyTrashed()->where('id', $id)->firstOrFail();

        $status->restore();

        return response()->json($status, 200);
    }

    //Permanently delete from status table and move to archived_status
    public function permanently_delete(string $id): JsonResponse
    {
        $deleted_status = Status::onlyTrashed()->where('id', $id)->firstOrFail();

        //Insert status details into archived_status table
        $data = [
            'id' => $deleted_status->id,
            'status_code' => $deleted_status->status_code,
            'created_at' => $deleted_status->created_at,
            'updated_at' => $deleted_status->updated_at,
            'deleted_at' => $deleted_status->deleted_at
        ];

        $this->createArchivedStatus($data);

        $deleted_status->forceDelete();

        return response()->json(['message' => 'Record moved to archives. It will remain for n amount of time
        before being permanently deleted'], 204);
    }
}
