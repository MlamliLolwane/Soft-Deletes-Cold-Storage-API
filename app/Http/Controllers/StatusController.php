<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateStatusRequest;
use App\Http\Requests\UpdateStatusRequest;
use App\Models\Status;
use Illuminate\Http\Request;

class StatusController extends Controller
{
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
        $status = Status::create($request->validated());

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

    public function restore(string $id)
    {
        $status = Status::onlyTrashed()->where('id', $id)->firstOrFail();

        $status->restore();

        return response()->json($status, 200);
    }
}
