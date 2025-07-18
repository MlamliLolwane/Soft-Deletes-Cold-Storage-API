<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreArchivedTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id' => 'required|integer|min:1', 
            'task_title' => 'required|string|max:50', 
            'task_definition' => 'required|string|max:250', 
            'status_id' => 'required|integer|min:1', 
            'user_id' => 'required|integer|min:1', 
            'created_at' => 'required|date',
            'updated_at' => 'required|date',
            'deleted_at' => 'required|date'
        ];
    }
}
