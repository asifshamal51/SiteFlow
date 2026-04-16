<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'location',
        'description',
        'type',
        'start_date',
        'end_date',
        'start_date_shamsi',
        'end_date_shamsi',
        'status',
        'currency_id',
        'initial_budget',
        'progress_percent',
        'created_by',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'project_users')
            ->withPivot('role_id', 'assigned_by', 'is_active')
            ->withTimestamps();
    }
}
