<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProposalFeedback extends Model
{
    use HasFactory;
    
    protected $table = 'proposal_feedbacks';

    protected $fillable = [
        'proposal_id',
        'reviewer_id',
        'feedback',
        'type',
        'attachment',
        'revision_requested',
    ];

    public function proposal()
    {
        return $this->belongsTo(Proposal::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }
    protected $casts = [
    'revision_fields' => 'array',
];

}