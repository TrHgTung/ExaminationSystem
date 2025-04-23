<?php
namespace App\Http\Controllers\Student;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Student;

class AuthController extends Controller
{
    // public function register(Request $request) {
    //     $thisYear = date('Y');
    //     $validateStudentInfo = $request->validate([
    //         'HoTen' => 'required',
    //         'Email' => 'required|email|unique:students',
    //         'Password' => 'required|min:6',
    //         'GioiTinh' => 'required|in:0,1', // 0 là nam, 1 là nữ
    //         'NamSinh' => 'required|digits:4|integer|min:1975|max:'.$thisYear,
    //         'DiemKinhNghiem' => 'numeric',
    //         'Status' => 'in:0,1', // 0 là bị ban, 1 là tài khoản oke
    //     ]);

    // // Tu dong tao StudentID
    // $lastStudent = Student::orderBy('StudentID', 'desc')->first();
    // // $studentCount1 = Student::count();
    // $studentCount = 0;
    
    //     if ($lastStudent) {
    //         $lastStudentID = $lastStudent->StudentID;
    //         $lastNumber = (int) substr($lastStudentID, 3);
    //         $studentCount = (int)$lastNumber;
    //         $newStudentID = 'SV_' . str_pad($studentCount + 1, 6, '0', STR_PAD_LEFT);
    //     }
    
    //     // Generate new StudentID
    //     else {
    //         $newStudentID = 'SV_000001'; // sinh vien dau tien
    //     }
    
    //     $createStudent = Student::create([
    //         'StudentID' => $newStudentID,
    //         'HoTen' => $validateStudentInfo['HoTen'],
    //         'Email' => $validateStudentInfo['Email'],
    //         'Password' => bcrypt($validateStudentInfo['Password']),
    //         'GioiTinh' => $validateStudentInfo['GioiTinh'],
    //         'NamSinh' => $validateStudentInfo['NamSinh'],
    //         'DiemKinhNghiem' => 0.00,
    //         'Status' => '1',
    //         // 'StudentID' => $newStudentID,
    //         // 'HoTen' => $request->input('HoTen'),
    //         // 'Email' => $request->input('Email'),
    //         // 'Password' => bcrypt($request->input('Password')),
    //         // 'GioiTinh' => $request->input('GioiTinh'),
    //         // 'NamSinh' => $request->input('NamSinh'),
    //         // 'DiemKinhNghiem' => 0.00,
    //         // 'Status' => '1',
    //     ]);

    //     if (!$createStudent) {
    //         return response()->json([
    //             'message' => 'Khong luu duoc thong tin sinh vien',
    //         ], 500);
    //     }
    
    //     $token = $createStudent->createToken('api_token')->plainTextToken;
    
    //     return response()->json([
    //         'token' => $token,
    //         'student_info' => $createStudent,
    //     ], 201);
    // }

    public function login(Request $request) {
        // $getStudent = Student::where('Email', $request->input('Email'))->first();

        $validateStudentInfo = $request->validate([
            'Email' => 'required|email',
            'Password' => 'required',
        ]);

        // Tìm sinh viên theo email (không phân biệt hoa thường)
        $email = strtolower($validateStudentInfo['Email']);
        $getStudent = Student::whereRaw('lower(Email) = ?', [$email])->first();
        $getStudentEmail = $getStudent->Email;
    
        if (Hash::check($validateStudentInfo['Password'], $getStudent->Password)) {
            
            $token = $getStudent->createToken('api_token')->plainTextToken;
    
            return response()->json([
                'message' => 'Xac thuc thanh cong',
                // 'student_info' => $getStudent,
                'student_email' => $getStudentEmail,
                'token' => $token,
            ], 200);
        }
    
        else {
            return response()->json([
                'message' => 'Xac thuc that bai'
            ], 401);
        }
    }

    public function logout(Request $req){
        // auth()->user()->tokens()->delete();
        $getStudent = auth()->user();

        if($getStudent) {
            $getStudent->tokens()->delete();

            return response()->json([
                'message' => 'Logged out',
                'user_info' => $getStudent,
            ], 200);
        }
        return response()->json([
            'message' => 'Token not found',
        ], 401);
    }

    public function profile(Request $req) {
        $getStudent = auth()->user();
        // $getStudentEmail = auth()->user()->Email;

        if($getStudent) {
            return response()->json([
                'student_info' => $getStudent,
                // 'student_email' => $getStudentEmail,
            ], 200);
        }
        else return response()->json([
            'message' => 'Xac thuc that bai',
        ], 401);
    }

    public function updateProfile(Request $request) {
        $getStudent = auth()->user();
        $validateStudentInfo = $request->validate([
            'HoTen' => '', // không cần validate
            // 'Email' => 'required|email', // email không cho sửa
            'Password' => 'required|min:6',
            'GioiTinh' => 'in:0,1', // 0 là nam, 1 là nữ
            // 'NamSinh' => 'required|digits:4|integer|min:1975|max:'.date('Y'), // năm sinh không cho sửa
            // 'DiemKinhNghiem' => 'numeric',
            // 'Status' => 'in:0,1', // 0 là bị ban, 1 là tài khoản oke
        ]);

        if($getStudent) {
            $getStudent->update([
                'HoTen' => $validateStudentInfo['HoTen'],
                'GioiTinh' => $validateStudentInfo['GioiTinh'],
                'Password' => $validateStudentInfo['Password'],
                // 'Status' => $validateStudentInfo['Status'],
            ]);
    
            return response()->json([
                'message' => 'Cap nhat thong tin thanh cong',
            ], 200);
        }
        else return response()->json([
            'message' => 'Cap nhat that bai - Loi xac thuc',
        ], 401);
    }
}
