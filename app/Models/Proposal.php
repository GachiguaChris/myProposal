<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proposal extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'submitted_by',
        'email',
        'organization_name',
        'address',
        'phone',
        'summary',
        'background',
        'activities',
        'budget',
        'status',
        'proposal_goals',
        'duration',
        'organization_type',
        'document',
        'project_category_id',
        'user_id',
    ];

    
    protected $casts = [
        'budget' => 'float',
    ];

    // Relationship: Proposal belongs to a User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function submittedByUser()
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    // Optional: Custom status accessor (currently unnecessary, but valid)
    public function getStatusAttribute($value)
    {
        return $value;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($proposal) {
            if (auth()->check()) {
                $proposal->user_id = auth()->id();
            }
        });
    }
    
    public function category()
    {
        return $this->belongsTo(ProjectCategory::class, 'project_category_id');
    }

    public function feedbacks()
    {
        return $this->hasMany(ProposalFeedback::class);
    }

    public function versions()
    {
        return $this->hasMany(ProposalVersion::class);
    }

    public function client()
    {
        
        return $this->belongsTo(Client::class, 'organization_name', 'company');
    }
    
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
} 