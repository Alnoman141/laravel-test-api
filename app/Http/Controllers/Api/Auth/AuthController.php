<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Validator;
use Illuminate\Support\Str;
use App\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;



class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function register(Request $request){
        $validator = Validator::make(
            $request->all(),
            array_merge($this->getValidationRules())
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 403);
        } else {
            $user = new User();

            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->slug = Str::slug($request->name);
            try {
                $user->save();
                return response()->json(['success' => 'registration successful'], 200);
            } catch (\Exception $ex) {
                return response()->json(['error' => $ex->getMessage()], 403);
            }
        }
    }

    public function login(Request $request){
        $credentials = $request->only('email', 'password');
        if (!Auth::attempt($credentials)) {
            return response()->json(new JsonResponse([], 'login_error'), Response::HTTP_UNAUTHORIZED);
        }

        $user = $request->user();

        $tokenResult  = $user->createToken('authToken')->plainTextToken;
        
        return response()->json([
            'access_token' => $tokenResult,
            'token_type' => 'bearer',
            'user' => auth()->user()
        ]);

    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->user()->currentAccessToken()->delete();
        return response()->json((new JsonResponse())->success(['message' => 'logout successful']), Response::HTTP_OK);
    }

    public function changePassword(Request $request, $slug){
        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 403);
        } else {
            $user = User::where('slug', $slug)->first();
            $oldPassword = $user->password;
            if (Hash::check($request->old_password,$oldPassword)) {
                $user->password = Hash::make($request->password);
                $user->save();
                return response()->json(['success' => 'Password Changed Successful'], 200);
            } else {
                return response()->json(['error' => 'Password doesnot matched'], 403);
            }
        }

    }

    
    private function getValidationRules()
    {
        return [
            'name' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|string|confirmed',
        ];
    }
}
