<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function user(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'user' => $user
        ]);
    }

    public function updateUser(Request $request)
    {
        $user = $request->user();
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users,email,' . $user->id,
            'phone' => 'required|string|max:14|unique:users,phone,' . $user->id,
            'address' => 'required|string|max:255',
            'store_name' => 'nullable',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->store_name = $request->store_name;
        $user->update();

        return response()->json([
            'message' => 'Account updated successfully!',
            'user' => $user
        ]);
    }

    public function changeUserPassword(Request $request)
    {
        $user = $request->user();
        $request->validate([
            'password' => 'required|string|min:8'
        ]);

        $user->password = bcrypt($request->password);
        $user->update();

        return response()->json([
            'message' => 'Password updated successfully!'
        ]);
    }

    public function destroyUser(Request $request)
    {
        $user = $request->user();
        User::destroy($user->id);

        return response()->json([
            'message' => 'Account deleted successfully!'
        ]);
    }

    public function allUsers(Request $request)
    {
        if(!$request->user()->isAdmin()){
            return response()->json([
                'message' => 'Unauthorized',
            ]);
        }
        $users = User::paginate(10);

        return response()->json([
            'message' => 'Users fetched successfully',
            'users' => $users
        ]);
    }

    public function update(Request $request, User $user)
    {
        $isAdmin = $request->user()->isAdmin();
        if(!$isAdmin){
            return response()->json([
                'message' => 'Unauthorized',
            ]);
        }

        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users,email,' . $user->id,
            'phone' => 'required|string|max:14|unique:users,phone,' . $user->id,
            'address' => 'required|string|max:255',
            'account_type' => 'string',
            'store_name' => 'nullable',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->store_name = $request->store_name;
        $user->account_type = $request->account_type;
        $user->update();

        return response()->json([
            'message' => 'User Account updated successfully!',
            'user' => $user
        ]);
    }

    public function destroy(Request $request, User $user)
    {
        $isAdmin = $request->user()->isAdmin();
        if(!$isAdmin){
            return response()->json([
                'message' => 'Unauthorized',
            ]);
        }

        User::destroy($user->id);

        return response()->json([
            'message' => 'User Account deleted successfully!'
        ]);
    }

    public function changeUsersPassword(Request $request, User $user)
    {
        $isAdmin = $request->user()->isAdmin();
        if(!$isAdmin){
            return response()->json([
                'message' => 'Unauthorized',
            ]);
        }
        $request->validate([
            'password' => 'required|string|min:8'
        ]);

        $user->password = bcrypt($request->password);
        $user->update();

        return response()->json([
            'message' => 'Password updated successfully!'
        ]);
    }
}
