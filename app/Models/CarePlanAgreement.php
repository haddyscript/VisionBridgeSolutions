<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarePlanAgreement extends Model
{
    protected $fillable = [
        'project_id',
        'maintenance_plan_id',
        'agreed_at',
        'ip_address',
        'user_agent',
    ];

    protected function casts(): array
    {
        return [
            'agreed_at' => 'datetime',
        ];
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function maintenancePlan()
    {
        return $this->belongsTo(MaintenancePlan::class);
    }
}
