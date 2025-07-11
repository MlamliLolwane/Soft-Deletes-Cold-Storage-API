<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id',
        'task_title',
        'task_definition',
        'status_id',
        'user_id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public $incrementing = true;


    public function status(): BelongsTo
    {
        return $this->status(Status::class);
    }

    public function user(): BelongsToMany
    {
        return $this->user(User::class);
    }
}
