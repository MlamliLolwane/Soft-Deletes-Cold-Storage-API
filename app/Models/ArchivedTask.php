<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ArchivedTask extends Model
{
    use HasFactory, SoftDeletes;
    
    public $table = 'archived_tasks';

    public $fillable = ['id', 'task_title', 'task_definition', 'status_id', 'user_id', 'created_at', 'updated_at', 'deleted_at'];
}
