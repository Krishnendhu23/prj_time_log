<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkLogUserTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'work_log_user_entry_id',
        'project_id',
        'task_description',
        'log_hours',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function entry()
    {
        return $this->belongsTo(WorkLogUserEntry::class, 'work_log_user_entry_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}
