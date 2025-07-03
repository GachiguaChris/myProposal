<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProjectCategory;
use Illuminate\Http\Request;

class ProjectCategoryController extends Controller
{
 public function index()
 {
     $categories = ProjectCategory::all();
     return view('Admin.project_categories.index', compact('categories'));
 }
    public function create()
    {
        return view('admin.project_categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:project_categories,name',
            'budget' => 'required|numeric|min:0',
        ]);

        ProjectCategory::create($request->only('name', 'budget'));

        return redirect()->route('admin.project-categories.index')->with('success', 'Project Category created.');
    }

    public function edit(ProjectCategory $projectCategory)
    {
        
        return view('admin.project_categories.edit', compact('projectCategory'));
    }

    public function update(Request $request, ProjectCategory $projectCategory)
    {
        $request->validate([
            'name' => 'required|unique:project_categories,name,' . $projectCategory->id,
            'budget' => 'required|numeric|min:0',
        ]);

        $projectCategory->update($request->only('name', 'budget'));

        return redirect()->route('admin.project-categories.index')->with('success', 'Project Category updated.');
    }

    public function destroy(ProjectCategory $projectCategory)
    {
        $projectCategory->delete();

        return redirect()->route('admin.project-categories.index')->with('success', 'Project Category deleted.');
    }

    public function show($id)
{
   //dd('hello world');
      $categories = ProjectCategory::all();  // Make sure this line is present
    return view('Admin.project_categories.index', compact('categories'));
   // or just return some message or view if you want
}


}
