<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = DB::table('user')
            ->leftJoin('role_user', 'user.iduser', '=', 'role_user.iduser')
            ->leftJoin('role', 'role_user.idrole', '=', 'role.idrole')
            ->select(
                'user.iduser',
                'user.nama',
                'user.email',
                'role.nama_role',
                'role_user.status'
            )
            ->orderBy('user.nama', 'asc')
            ->get();

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:user',
            'password' => 'required|string|min:8',
            'idrole' => 'required|exists:role,idrole',
        ]);

        $user = User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->roles()->attach($request->idrole, ['status' => 1]);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    public function edit($id)
    {
        try {
            $user = User::with('roles')->findOrFail($id);
            $roles = Role::all();

            // Debug untuk melihat data user dan roles
            \Log::info('Loading user edit page', [
                'user_id' => $id,
                'user_exists' => isset($user),
                'user_nama' => $user->nama ?? 'null',
                'roles_count' => $roles->count(),
                'user_roles_count' => $user->roles->count(),
                'user_roles' => $user->roles->toArray()
            ]);

            return view('admin.users.edit', compact('user', 'roles'));
        } catch (\Exception $e) {
            \Log::error('Error loading user edit page', [
                'user_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:user,email,' . $id . ',iduser',
            'password' => 'nullable|string|min:8',
            'idrole' => 'required|exists:role,idrole',
            'status' => 'required|boolean',
        ]);

        $user = User::findOrFail($id);

        $userData = [
            'nama' => $request->nama,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);

        $user->roles()->sync([$request->idrole => ['status' => $request->status]]);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }
}
