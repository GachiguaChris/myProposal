<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'company',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'zip',
        'country',
        'notes',
        'status'
    ];

    /**
     * Get the proposals for the client.
     */
    public function proposals()
    {
        return $this->hasMany(Proposal::class);
    }
}