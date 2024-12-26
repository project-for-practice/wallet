<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
// use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
class UserController extends Controller
{
    public function index(Request $request)
    {

        $users = User::with('roles:id,name')->select('id', 'name', 'email', 'status', 'created_at')->latest()->get();
        $usersData = $users->map(function ($user) {
            $usersData = $user->toArray();
            $usersData['roles'] = $user['roles']->pluck('name');
            return $usersData;
        });

        return Inertia::render('UserList', [
            'users' => $usersData,
            'message' => session('message'),
            'filters' => $request->only(['status', 'role', 'search']),
        ]);
    }
    public function updateStatus(Request $request, $id)
    {

        $request->validate([
            'status' => ['required', 'in:active,inactive,suspended'],
        ]);
        $user = User::findOrFail($id);
        // dd($user);
        $user->status = $request->status;
        $user->save();
        session()->flash('message', 'User status updated successfully!');
        return redirect()->route('users-list');
    }
    public function filterUsers(Request $request)
    {
        $query = User::query();

        if ($request->has('status') && in_array($request->status, ['active', 'inactive', 'suspended'])) {
            $query->where('status', $request->status);
        }
        if ($request->has('role') && in_array($request->role, ['admin', 'user'])) {
            $query->when($request->has('role'), function ($q) use ($request) {
                return $q->whereHas('roles', function ($query) use ($request) {
                    $query->where('name', $request->role);
                });
            });
        }
        if ($request->has('search')) {
            $query->where('email', 'like', '%' . $request->search . '%');
        }
        $users = $query->get();

        return response()->json($users);
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|exists:roles,name',
        ]);
        $role = Role::where('name', $validated['role'])->first();
        if ($role) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);
            $user->roles()->attach($role); // Associate the role with the user
            $user->save();
            session()->flash('message', 'User added successfully!');
            return redirect()->route('users-list');
        } else {
            session()->flash('message', 'Role not found');
            return redirect()->route('users-list');
        }

    }
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('users-list');
    }

    public function restore($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->restore();
        return redirect()->route('users.index');
    }
    public function forceDelete($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->forceDelete();
        return redirect()->route('users.index');
    }
}
