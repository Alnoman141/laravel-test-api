<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(){
        $users = User::all();
        if(isset($users)){
            return response()->json(['users' => $users ], 200);
        } else {
            return response()->json(['error' => 'No user not found']);
        }
        
    }

    public function profile(){
        $user = Auth::user();

        if(isset($user)){
            return response()->json(['user' => $user ], 200);
        } else {
            return response()->json(['error' => 'User not found']);
        }
    }

    public function show($slug){
        $user = User::where('slug', $slug)->first();
        if(isset($user)){
            return response()->json(['user' => $user ], 200);
        } else {
            return response()->json(['error' => 'User not found']);
        }
    }

    public function update(Request $request){
        if(isset($user)){
            $user = Auth::user();
            $user->phone = $request->phone;
            $user->country = $request->country;

            try {
                $user->save();
            } catch (\Exception $ex) {
                return response()->json(['error' => $ex->getMessage()], 403);
            }
        } else {
            return response()->json(['error' => 'User not found']);
        }
        
    }

    public function delete($slug){
        $user = User::where('slug', $slug)->first();
        if(isset($user)){
            $user->delete();
            return response()->json(['success' => 'User deleted'], 200);
        } else {
            return response()->json(['error' => 'User not found']);
        }
    }
    
}
