<?php
 namespace App\Exports; // Maatwebsite\Excel\Concerns
// App\Exports\ProposalsExport.php

use App\Models\Proposal;
use Maatwebsite\Excel\Concerns\FromCollection;

class ProposalsExport implements FromCollection
{
    public function collection()
    {
        return Proposal::with(['category', 'user'])->get();
    }
}
