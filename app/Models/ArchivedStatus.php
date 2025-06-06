<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class ArchivedStatus extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'archived_status';

    protected $fillable = ['id', 'status_code', 'created_at', 'updated_at', 'deleted_at'];
}
