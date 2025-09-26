<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkLogUserEntry extends Model
{
    use HasFactory,SoftDeletes;
    
    protected $fillable = [
        'user_id',
        'date',
        'create_at',
        'updated_at',
        'deleted_at'
    ];

    public function tasks()
    {
        return $this->hasMany(WorkLogUserTask::class, 'work_log_user_entry_id');
    }

    // Accessor for formatted date
    public function getFormattedDateAttribute()
    {
        return Carbon::parse($this->date)->format('d M Y'); // e.g., 25 Sep,2025
    }

    
}
