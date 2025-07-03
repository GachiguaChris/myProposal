<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Proposal;

class ClientProfileController extends Controller
{
    public function index()
    {
        // Step 1: Get all accepted proposals
        $acceptedProposals = Proposal::where('status', 'accepted')->get();

        // Step 2: Group by unique organization_name
        $groupedByOrg = $acceptedProposals->groupBy('organization_name');

        $autoClients = collect();

        foreach ($groupedByOrg as $orgName => $proposals) {
            if (!$orgName) continue; // Skip empty orgs

            // Check if this org already exists in clients table
            $existing = Client::where('company', $orgName)->first();

            if ($existing) {
                $autoClients->push($existing);
            } else {
                $first = $proposals->first();

                // Create a temporary client object (not saved in DB)
                $client = new Client();
                $client->name = $first->organization_name;
                $client->company = $first->organization_name;
                $client->email = $first->email;
                $client->phone = $first->phone;
                $client->address = $first->address;

                $autoClients->push($client);
            }
        }

        // Step 3: Get manually created clients
        $manualClients = Client::all();

        // Step 4: Merge both, avoid duplicates by company name
        $clients = $manualClients
            ->merge($autoClients)
            ->unique('company')
            ->values();

        return view('client.profile', compact('clients'));
    }

    
}
