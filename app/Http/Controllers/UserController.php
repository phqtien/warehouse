<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index()
    {
        return view('/admin/users');
    }

    public function fetchUsers(Request $request)
    {
        if ($request->ajax()) {
            $users = User::join('roles', 'roles.user_id', '=', 'users.id')
                ->select(['users.id', 'users.name', 'users.email', 'roles.name as role', 'users.created_at']);

            return DataTables::of($users)
                ->filterColumn('role', function ($query, $keyword) {
                    $query->where('roles.name', 'like', "%{$keyword}%");
                })
                ->editColumn('created_at', function ($user) {
                    return $user->created_at->setTimezone('Asia/Ho_Chi_Minh')->format('d-m-Y H:i:s');
                })
                ->make(true);
        }

        return abort(404);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:5|confirmed',
            'role' => 'required|in:user,admin',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        Role::create([
            'user_id' => $user->id,
            'name' => $request->role,
        ]);

        return response()->json([
            'message' => 'User created successfully.',
        ], 201);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json([
            'message' => 'User deleted successfully.'
        ], 200);
    }
}
