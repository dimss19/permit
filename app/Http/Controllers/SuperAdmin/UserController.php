<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'divisi')->orderBy('created_at', 'desc');

        if ($request->filled('status')) {
            if ($request->status === 'Aktif') {
                $query->where('is_active', true);
            } elseif ($request->status === 'Nonaktif') {
                $query->where('is_active', false);
            }
        }

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('username', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%");
            });
        }

        $users = $query->paginate(15)->withQueryString();

        return view('superadmin.users.index', compact('users'));
    }

    public function create()
    {
        $divisions = \App\Models\Division::orderBy('name')->get();
        return view('superadmin.users.create', compact('divisions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|string|email|max:255', // Removed unique constraint as requested
            'password' => 'required|string|min:6|confirmed', // Must have password_confirmation field
        ]);

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'divisi',
            'is_active' => true,
        ]);

        return redirect('/superadmin/users')->with('success', 'Akun Divisi berhasil dibuat.');
    }

    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        $divisions = \App\Models\Division::orderBy('name')->get();
        return view('superadmin.users.edit', compact('user', 'divisions'));
    }

    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $rules = [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|string|email|max:255',
        ];

        if ($request->filled('password')) {
            $rules['password'] = 'required|string|min:6|confirmed';
        }

        $request->validate($rules);

        $user->name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect('/superadmin/users')->with('success', 'Akun Divisi berhasil diperbarui.');
    }

    public function updateStatus(Request $request, $id)
    {
        $user = User::where('role', 'divisi')->findOrFail($id);
        
        // Toggle status
        $user->update([
            'is_active' => !$user->is_active
        ]);

        $statusText = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->back()->with('success', "Akun divisi {$user->name} berhasil {$statusText}.");
    }

    public function resetPassword(Request $request, $id)
    {
        $user = User::where('role', 'divisi')->findOrFail($id);
        
        $request->validate([
            'password' => 'required|string|min:6',
        ]);

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return redirect()->back()->with('success', 'Password berhasil direset.');
    }

    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        
        // Cek jika user punya permit, maka tolak penghapusan. 
        // Karena ada relasi ke Permits table. Kita bisa biarkan dihapus jika cascade, tapi default lebih baik tolak atau hapus.
        // Di sini saya asumsikan kita bisa menghapusnya langsung. 
        // Namun, jika ada error integrity constraint, lebih baik dibungkus try-catch.
        try {
            $user->delete();
            return redirect('/superadmin/users')->with('success', 'Akun berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect('/superadmin/users')->withErrors(['Gagal menghapus akun. Akun ini mungkin sudah terikat dengan data Permit. Coba Nonaktifkan akun saja.']);
        }
    }
}
