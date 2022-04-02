<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Stripe;

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
        if (Auth::attempt($fields)) {
            $request->session()->regenerate();
            $user = Auth::guard('web')->user();
            $isAdmin = $user->name === 'admin' && $fields['password'] === 'admin';
            return response([
                'isAdmin' => $isAdmin,
                'success' => true,
                'message' => "welcome $user->name",
                'user' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'address' => $user->address->street,
                    'state' => $user->address->city,
                    'zip' => $user->address->zip,
                    'isAdmin' => $isAdmin
                ]
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
    public function auth()
    {
        $user = Auth::guard('web')->user();
        $isAdmin = false;
        if ($user->name === 'admin' && Hash::check('admin', $user->password))
            $isAdmin = true;
        return response([
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'address' => $user->address->street,
            'state' => $user->address->city,
            'zip' => $user->address->zip,
            'isAdmin' => $isAdmin
        ]);
    }
    public function save(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric|min:100',
            'quantity' => 'required|numeric|min:1',
            'featured' => 'required',
            'description' => 'required|string|min:5',
            'category' => 'required|string'
        ]);

        $category = Category::where('name', '=', $fields['category'])->first();
        if (!$category)
            return response([
                'errors' => [
                    'category' => ['invalid category']
                ]
            ], 422);

        $fields['category_id'] = $category->id;
        unset($fields['category']);

        $request->session()->put('product', $fields);

        return response([
            'message' => 'product stored'
        ], 201);
    }
}