<?php

namespace App\Http\Controllers\Pelaksana;

use App\Http\Controllers\Controller;
use App\Models\Template;
use Illuminate\Http\Request;

class TemplateController extends Controller
{
    public function index()
    {
        $templates = Template::latest()->get();
        return view('dashboard.pelaksana.templates.index', compact('templates'));
    }
}
