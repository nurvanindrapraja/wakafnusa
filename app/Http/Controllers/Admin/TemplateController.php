<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TemplateController extends Controller
{
    public function index()
    {
        $templates = Template::latest()->get();
        return view('dashboard.admin.templates.index', compact('templates'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'file' => 'required|file|max:10240', // 10MB
            'description' => 'nullable|string|max:255',
        ]);

        $filePath = $request->file('file')->store('templates', 'public');

        Template::create([
            'name' => $request->name,
            'file_path' => $filePath,
            'description' => $request->description,
        ]);

        return back()->with('success', 'Template berhasil diunggah.');
    }

    public function destroy(Template $template)
    {
        Storage::disk('public')->delete($template->file_path);
        $template->delete();

        return back()->with('success', 'Template berhasil dihapus.');
    }
}
