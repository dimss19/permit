<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Division;
use Illuminate\Http\Request;

class DivisionController extends Controller
{
    public function index()
    {
        $divisions = Division::orderBy('name')->get();
        return view('superadmin.divisions.index', compact('divisions'));
    }

    public function create()
    {
        return view('superadmin.divisions.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:divisions,name'
        ]);
        Division::create(['name' => $request->name]);
        return redirect('/superadmin/divisions')->with('success', 'Divisi berhasil ditambahkan.');
    }

    public function edit(Division $division)
    {
        return view('superadmin.divisions.form', compact('division'));
    }

    public function update(Request $request, Division $division)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:divisions,name,' . $division->id
        ]);
        $division->update(['name' => $request->name]);
        return redirect('/superadmin/divisions')->with('success', 'Divisi berhasil diperbarui.');
    }

    public function destroy(Division $division)
    {
        $division->delete();
        return redirect('/superadmin/divisions')->with('success', 'Divisi berhasil dihapus.');
    }
}
