<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProposalVersion extends Model
{
    use HasFactory;

    protected $fillable = [
        'proposal_id',
        'content',
        'version_number',
        'created_by',
    ];

    public function proposal()
    {
        return $this->belongsTo(Proposal::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}