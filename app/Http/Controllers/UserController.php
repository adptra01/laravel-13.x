<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Merchant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $sort = $request->input('sort', 'created_at');
        $direction = $request->input('direction', 'desc');
        $perPage = (int) $request->input('per_page', 10);

        $users = User::query()
            ->when($search, fn ($query) => $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            }))
            ->orderBy($sort, $direction)
            ->paginate($perPage)
            ->withQueryString();

        return view('users.index', compact('users', 'search', 'sort', 'direction'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'role' => ['required', 'in:merchant,customer,admin'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        // Profil domain dibuat agar portal terkait langsung terpakai
        // (dashboard merchant/customer melakukan firstOrFail pada profil).
        $slug = Str::slug($validated['name']).'-'.Str::lower(Str::random(5));

        if ($validated['role'] === 'merchant') {
            Merchant::create([
                'user_id' => $user->id,
                'company_name' => $validated['name'],
                'slug' => $slug,
            ]);
        } elseif ($validated['role'] === 'customer') {
            Customer::create([
                'user_id' => $user->id,
                'company_name' => $validated['name'],
                'slug' => $slug,
            ]);
        }

        return redirect()->route('users.index')
            ->with('saved', 'Pengguna "'.$validated['name'].'" berhasil ditambahkan sebagai '.ucfirst($validated['role']).'.');
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'password' => ['nullable', 'confirmed', Password::defaults()],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if (filled($validated['password'] ?? null)) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('users.index')->with('saved', 'Data pengguna "'.$user->name.'" berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();

        return redirect()->route('users.index')->with('saved', 'Pengguna berhasil dihapus.');
    }
}
