<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;


class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware('idAdmin')->only(['logout', 'viewProfile', 'changePassword']); // chi cho phep admin thuc hien nhung ham nay
    }

    // post
    public function login(Request $request) {
        $validateAdmin = $request->validate([
            'Email' => 'required|email',
            'Password' => 'required',
        ]);

        $email = strtolower($validateAdmin['Email']);
        $getAdmin = Admin::whereRaw('lower(Email) = ?', [$email])->first();
        $getAdminEmail = $getAdmin->Email;
    
        if (Hash::check($validateAdmin['Password'], $getAdmin->Password)) {
            
            $token = $getAdmin->createToken('api_token')->plainTextToken;

            return response()->json([
                'message' => 'Xac thuc thanh cong',
                'admin_email' => $getAdminEmail,
                'token' => $token,
            ], 200);
        }
    
        else {
            return response()->json([
                'message' => 'Xac thuc that bai'
            ], 401);
        }
    }

    // post
    public function logout(Request $request){
        $getAdmin = auth()->user();

        if($getAdmin) {
            $getAdmin->tokens()->delete();

            return response()->json([
                'message' => 'Logged out',
                'user_info' => $getAdmin,
            ], 200);
        }
        else {
            return response()->json([
                'message' => 'Logged out',
                'user_info' => null,
            ], 401);
        }
    }

    // get
    public function viewProfile(Request $request) {
        $getAdmin = auth()->user();

        if($getAdmin) {
            return response()->json([
                'message' => 'Logged out',
                'user_info' => $getAdmin,
            ], 200);
        }
        else {
            return response()->json([
                'message' => 'Khong tim thay user'
            ], 404);
        }
    }

    // patch
    public function changePassword(Request $request) {
        $getAdmin = auth()->user();

        if($getAdmin) {
            $validateChangePassword = $request->validate([
                'OldPassword' => 'required',
                'NewPassword' => 'required|min:6',
            ]);

            if (Hash::check($validateChangePassword['OldPassword'], $getAdmin->Password)) {
                $getAdmin->update([
                    'Password' => bcrypt($validateChangePassword['NewPassword']),
                ]);

                return response()->json([
                    'message' => 'Thay doi mat khau thanh cong',
                    'user_info' => $getAdmin,
                ], 200);
            }
            else {
                return response()->json([
                    'message' => 'Mat khau cu khong chinh xac'
                ], 401);
            }
        }
        else {
            return response()->json([
                'message' => 'Khong tim thay user'
            ], 404);
        }
    }
}

