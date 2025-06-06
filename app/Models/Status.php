<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Status extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'status';
    protected $fillable = ['id', 'status_code', 'created_at', 'updated_at', 'deleted_at'];
    
    public $incrementing = true;

    public function task(): HasOne
    {
        return $this->task(Task::class);
    }
}
