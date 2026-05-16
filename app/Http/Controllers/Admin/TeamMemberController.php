<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TeamMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TeamMemberController extends Controller
{
    public function index(Request $request)
    {
        $query = TeamMember::orderBy('order_weight')->latest();

        if ($request->category) {
            $query->where('category', $request->category);
        }

        if ($request->search) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        $members = $query->paginate(15)->withQueryString();

        return view('dashboard.admin.team-members.index', compact('members'));
    }

    public function create()
    {
        return view('dashboard.admin.team-members.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'category' => 'required|in:NAZHIR,PENILAI,PENGEMBANG',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'order_weight' => 'nullable|integer',
        ]);

        $data = $request->all();

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('team-members', 'public');
        }

        TeamMember::create($data);

        return redirect()->route('admin.team-members.index')->with('success', 'Anggota tim berhasil ditambahkan.');
    }

    public function edit(TeamMember $teamMember)
    {
        return view('dashboard.admin.team-members.edit', compact('teamMember'));
    }

    public function update(Request $request, TeamMember $teamMember)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'category' => 'required|in:NAZHIR,PENILAI,PENGEMBANG',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'order_weight' => 'nullable|integer',
        ]);

        $data = $request->all();

        if ($request->hasFile('photo')) {
            if ($teamMember->photo) {
                Storage::disk('public')->delete($teamMember->photo);
            }
            $data['photo'] = $request->file('photo')->store('team-members', 'public');
        }

        $teamMember->update($data);

        return redirect()->route('admin.team-members.index')->with('success', 'Data anggota tim berhasil diperbarui.');
    }

    public function destroy(TeamMember $teamMember)
    {
        if ($teamMember->photo) {
            Storage::disk('public')->delete($teamMember->photo);
        }
        $teamMember->delete();

        return redirect()->route('admin.team-members.index')->with('success', 'Anggota tim berhasil dihapus.');
    }
}
