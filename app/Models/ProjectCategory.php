<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectCategory extends Model
{
    protected $fillable = ['name', 'budget'];

    public function proposals()
    {
        return $this->hasMany(Proposal::class, 'project_category_id');
    }
}
