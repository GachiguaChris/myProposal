<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Proposal;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class DocumentController extends Controller
{
    /**
     * Display a listing of the documents.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $documents = Document::with(['proposal', 'client', 'user'])->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.documents.index', compact('documents'));
    }

    /**
     * Show the form for creating a new document.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $proposals = Proposal::all();
        $clients = Client::all();
        return view('admin.documents.create', compact('proposals', 'clients'));
    }

    /**
     * Store a newly created document in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'required|file|max:10240',
            'proposal_id' => 'nullable|exists:proposals,id',
            'client_id' => 'nullable|exists:clients,id',
        ]);

        $file = $request->file('file');
        $path = $file->store('documents', 'public');
        
        Document::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'file_path' => $path,
            'file_type' => $file->getClientMimeType(),
            'file_size' => $file->getSize(),
            'proposal_id' => $validated['proposal_id'],
            'client_id' => $validated['client_id'],
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('admin.documents.index')
            ->with('success', 'Document uploaded successfully.');
    }

    /**
     * Display the specified document.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $document = Document::with(['proposal', 'client', 'user'])->findOrFail($id);
        return view('admin.documents.show', compact('document'));
    }

    /**
     * Show the form for editing the specified document.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $document = Document::findOrFail($id);
        $proposals = Proposal::all();
        $clients = Client::all();
        return view('admin.documents.edit', compact('document', 'proposals', 'clients'));
    }

    /**
     * Update the specified document in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $document = Document::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'nullable|file|max:10240',
            'proposal_id' => 'nullable|exists:proposals,id',
            'client_id' => 'nullable|exists:clients,id',
        ]);

        $data = [
            'name' => $validated['name'],
            'description' => $validated['description'],
            'proposal_id' => $validated['proposal_id'],
            'client_id' => $validated['client_id'],
        ];

        if ($request->hasFile('file')) {
            // Delete old file
            Storage::disk('public')->delete($document->file_path);
            
            // Store new file
            $file = $request->file('file');
            $path = $file->store('documents', 'public');
            
            $data['file_path'] = $path;
            $data['file_type'] = $file->getClientMimeType();
            $data['file_size'] = $file->getSize();
        }

        $document->update($data);

        return redirect()->route('admin.documents.index')
            ->with('success', 'Document updated successfully.');
    }

    /**
     * Remove the specified document from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $document = Document::findOrFail($id);
        
        // Delete file from storage
        Storage::disk('public')->delete($document->file_path);
        
        // Delete record
        $document->delete();

        return redirect()->route('admin.documents.index')
            ->with('success', 'Document deleted successfully.');
    }

    /**
     * Download the specified document.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function download($id)
    {
        $document = Document::findOrFail($id);
        return Storage::disk('public')->download($document->file_path, $document->name);
    }
}