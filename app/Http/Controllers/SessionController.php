<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionController extends Controller
{
    public function register(Request $request)
    {
        $fields = $request->validate([
            'username' => 'required|string|min:4',
            'email' => 'required|string|unique:users,email|email',
            'password' => 'required|string|confirmed|min:6',
            'phone' => 'required|numeric|digits:9',
            'address' => 'required|string',
            'state' => 'required|string',
            'zipCode' => 'required|numeric|digits:5'
        ]);

        // $user = User::create([
        //     'name' => $fields['username'],
        //     'email' => $fields['email'],
        //     'phone' => $fields['phone'],
        //     'password' => bcrypt($fields['password'])
        // ]);
        // $address = UserAddress::create([
        //     'user_id' => $user->id,
        //     'address' => $fields['address'],
        //     'state' => $fields['state'],
        //     'zipCode' => $fields['zipCode']
        // ]);
        $user = new User();
        $address = new UserAddress();

        $user->name = $fields['username'];
        $user->email = $fields['email'];
        $user->phone = $fields['phone'];
        $user->password = bcrypt($fields['password']);
        $user->save();

        $address->user_id = $user->id;
        $address->street = $fields['address'];
        $address->city = $fields['state'];
        $address->zip = $fields['zipCode'];
        $address->save();

        return response([
            'message' => 'user saved successfully',
            'user' => $user,
            'address' => $address
        ], 201);
    }
    public function login(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|string|email|exists:users,email',
            'password' => 'required|string'
        ]);
        $user = User::where('email', $fields['email'])->first();
        // if (!$user || !Hash::check($fields['password'], 'asdfjosjfsjf'))
        //     return response([
        //         'message' => 'bad credentilas'
        //     ]);
        if (Auth::attempt($fields)) {
            $request->session()->regenerate();
            return response([
                'success' => true,
                'message' => "welcome $user->name",
                'user' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone
            ], 200);
        }
        return response([
            'success' => false,
            'message' => 'wrong credentilas'
        ], 401);
    }
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return response([
            'success' => true,
            'message' => 'loged out successfully'
        ]);
    }
}