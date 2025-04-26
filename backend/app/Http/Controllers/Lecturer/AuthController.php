<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Lecturer;

class AuthController extends Controller // LECTURER
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware('isLecturer')->only(['logout', 'viewProfile']); // chi cho phep admin thuc hien nhung ham nay
    }

    // post
    public function login(Request $request) {
        $validateLecturer = $request->validate([
            'Email' => 'required|email',
            'Password' => 'required',
        ]);

        // Tìm sinh viên theo email (không phân biệt hoa thường)
        $email = strtolower($validateLecturer['Email']);
        $getLecturer = Lecturer::whereRaw('lower(Email) = ?', [$email])->first();
        $getLecturerEmail = $getLecturer->Email;
    
        if (Hash::check($validateLecturer['Password'], $getLecturer->Password)) {
            
            $token = $getLecturer->createToken('api_token')->plainTextToken;
    
            return response()->json([
                'message' => 'Xac thuc thanh cong',
                // 'info' => $getLecturer,
                'lecturer_email' => $getLecturerEmail,
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
        // auth()->user()->tokens()->delete();
        $getLecturer = auth()->user();

        if($getLecturer) {
            $getLecturer->tokens()->delete();

            return response()->json([
                'message' => 'Logged out',
                'user_info' => $getLecturer,
            ], 200);
        }
        else {
            return response()->json([
                'message' => 'Khong tim thay user'
            ], 404);
        }
    }
   
    // get
    public function viewProfile(Request $request) {
        $getLecturer = auth()->user();

        if($getLecturer) {
            return response()->json([
                'message' => 'Lay thong tin thanh cong',
                'user_info' => $getLecturer,
            ], 200);
        }
        else {
            return response()->json([
                'message' => 'Khong tim thay user'
            ], 404);
        }
    }
}
