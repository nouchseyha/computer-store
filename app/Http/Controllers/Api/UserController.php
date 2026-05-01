<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::withCount('orders')->latest();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }
        if ($request->filled('role')) {
            $query->where('is_admin', $request->role === 'admin');
        }

        return UserResource::collection($query->paginate($request->get('per_page', 20)));
    }

    public function show(User $user)
    {
        return new UserResource($user->loadCount('orders'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'is_admin' => 'boolean',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'is_admin' => $request->boolean('is_admin', false),
        ]);

        return new UserResource($user);
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'     => 'sometimes|string|max:255',
            'email'    => 'sometimes|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
            'is_admin' => 'boolean',
        ]);

        $data = $request->only(['name', 'email']);
        if ($request->filled('password')) $data['password'] = Hash::make($request->password);
        if ($request->has('is_admin'))    $data['is_admin']  = $request->boolean('is_admin');

        $user->update($data);
        return new UserResource($user);
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return response()->json(['message' => 'Cannot delete your own account.'], 403);
        }
        $user->delete();
        return response()->json(['message' => 'User deleted.']);
    }

    // Update own profile
    public function updateProfile(Request $request)
    {
        $user = $request->user();
        $request->validate([
            'name'     => 'sometimes|string|max:255',
            'phone'    => 'nullable|string|max:20',
            'address'  => 'nullable|string',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $data = $request->only(['name', 'phone', 'address']);
        if ($request->filled('password')) $data['password'] = Hash::make($request->password);

        $user->update($data);
        return new UserResource($user);
    }
}
