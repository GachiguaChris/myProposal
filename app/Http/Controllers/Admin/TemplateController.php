<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Template;
use App\Models\ProjectCategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use PDF;

class TemplateController extends Controller
{
    public function index()
    {
        $templates = Template::with('category', 'creator')->get();
        return view('admin.templates.index', compact('templates'));
    }

    public function create()
    {
        $categories = ProjectCategory::all();
        
        // Sample template content
        $sampleContent = <<<EOT
# [Project Title]

## Executive Summary
[Brief overview of the proposal - 2-3 sentences that summarize the entire proposal]

## Problem Statement
[Describe the problem or opportunity that this proposal addresses]

## Proposed Solution
[Outline your proposed solution to the problem]

### Key Features
- [Feature 1]
- [Feature 2]
- [Feature 3]

## Timeline
- **Phase 1 (Planning)**: [Start Date] - [End Date]
- **Phase 2 (Implementation)**: [Start Date] - [End Date]
- **Phase 3 (Evaluation)**: [Start Date] - [End Date]

## Budget
| Item | Cost |
|------|------|
| [Item 1] | $[Amount] |
| [Item 2] | $[Amount] |
| [Item 3] | $[Amount] |
| **Total** | $[Total Amount] |

## Expected Outcomes
- [Outcome 1]
- [Outcome 2]
- [Outcome 3]

## Team Members
- [Name], [Role]
- [Name], [Role]
- [Name], [Role]

## Contact Information
[Your Name]
[Your Email]
[Your Phone Number]
EOT;

        return view('admin.templates.create', compact('categories', 'sampleContent'));
    }

    public function store(Request $request)
    {
        try {
            // Validate the request
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'content' => 'nullable|string',
                'project_category_id' => 'nullable|exists:project_categories,id',
                'is_active' => 'boolean',
                'template_file' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            ]);

            // Set default values
            $validated['created_by'] = Auth::id();
            $validated['is_active'] = $request->has('is_active');
            
            // If no content is provided, set a default
            if (empty($validated['content'])) {
                $validated['content'] = 'Template content will be generated from the uploaded file.';
            }

            // Handle file upload if present
            if ($request->hasFile('template_file')) {
                $path = $request->file('template_file')->store('templates', 'public');
                $validated['file_path'] = $path;
            }

            // Create the template
            Template::create($validated);

            return redirect()->route('admin.templates.index')
                ->with('success', 'Template created successfully.');
        } catch (\Exception $e) {
            Log::error('Template creation failed: ' . $e->getMessage());
            
            // Notify all admin users
            $this->notifyAdminsOfError('Template creation failed', $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Template creation failed: ' . $e->getMessage());
        }
    }

    public function edit(Template $template)
    {
        $categories = ProjectCategory::all();
        return view('admin.templates.edit', compact('template', 'categories'));
    }

    public function update(Request $request, Template $template)
    {
        try {
            // Validate the request
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'content' => 'nullable|string',
                'project_category_id' => 'nullable|exists:project_categories,id',
                'is_active' => 'boolean',
                'template_file' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            ]);

            // Set default values
            $validated['is_active'] = $request->has('is_active');
            
            // If no content is provided, set a default
            if (empty($validated['content'])) {
                $validated['content'] = 'Template content will be generated from the uploaded file.';
            }

            // Handle file upload if present
            if ($request->hasFile('template_file')) {
                // Delete old file if exists
                if ($template->file_path) {
                    Storage::disk('public')->delete($template->file_path);
                }
                
                $path = $request->file('template_file')->store('templates', 'public');
                $validated['file_path'] = $path;
            }

            // Update the template
            $template->update($validated);

            return redirect()->route('admin.templates.index')
                ->with('success', 'Template updated successfully.');
        } catch (\Exception $e) {
            Log::error('Template update failed: ' . $e->getMessage());
            
            // Notify all admin users
            $this->notifyAdminsOfError('Template update failed', $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Template update failed: ' . $e->getMessage());
        }
    }

    public function destroy(Template $template)
    {
        try {
            // Delete file if exists
            if ($template->file_path) {
                Storage::disk('public')->delete($template->file_path);
            }
            
            $template->delete();

            return redirect()->route('admin.templates.index')
                ->with('success', 'Template deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Template deletion failed: ' . $e->getMessage());
            
            // Notify all admin users
            $this->notifyAdminsOfError('Template deletion failed', $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Template deletion failed: ' . $e->getMessage());
        }
    }
    
    /**
     * Download the template file
     *
     * @param Template $template
     * @return \Illuminate\Http\Response
     */
    public function download(Template $template)
    {
        if ($template->file_path) {
            return Storage::disk('public')->download($template->file_path, $template->name . '.pdf');
        } else {
            // Generate PDF from template content
            $pdf = PDF::loadView('admin.templates.pdf', compact('template'));
            return $pdf->download($template->name . '.pdf');
        }
    }
    
    /**
     * Notify all admin users about an error
     *
     * @param string $subject
     * @param string $errorMessage
     * @return void
     */
    private function notifyAdminsOfError($subject, $errorMessage)
    {
        try {
            // Get all admin users
            $admins = User::where('is_admin', true)->get();
            
            foreach ($admins as $admin) {
                // Create a notification record
                \App\Models\Notification::create([
                    'title' => $subject,
                    'message' => "Error details: " . $errorMessage,
                    'type' => 'danger',
                    'read' => false,
                    'user_id' => $admin->id
                ]);
            }
            
            Log::info('Admin notifications sent for error: ' . $subject);
        } catch (\Exception $e) {
            Log::error('Failed to notify admins: ' . $e->getMessage());
        }
    }
}