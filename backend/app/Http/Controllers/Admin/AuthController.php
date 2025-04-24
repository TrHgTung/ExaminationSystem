<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware('idAdmin')->only(['createStudent', 'createLecturer']); // chi cho phep admin thuc hien nhung ham nay
    }

    public function createStudent(Request $request) {
        $thisYear = date('Y');
        $validateStudentInfo = $request->validate([
            'HoTen' => 'required',
            'Email' => 'required|email|unique:students',
            'Password' => 'required|min:6',
            'GioiTinh' => 'required|in:0,1', // 0 là nam, 1 là nữ
            'NamSinh' => 'required|digits:4|integer|min:1975|max:'.$thisYear,
            'DiemKinhNghiem' => 'numeric',
            'Status' => 'in:0,1', // 0 là bị ban, 1 là tài khoản oke
        ]);

    // Tu dong tao StudentID
    $lastStudent = Student::orderBy('StudentID', 'desc')->first();
    // $studentCount1 = Student::count();
    $studentCount = 0;
    
        if ($lastStudent) {
            $lastStudentID = $lastStudent->StudentID;
            $lastNumber = (int) substr($lastStudentID, 3);
            $studentCount = (int)$lastNumber;
            $newStudentID = 'SV_' . str_pad($studentCount + 1, 6, '0', STR_PAD_LEFT);
        }
    
        // Generate new StudentID
        else {
            $newStudentID = 'SV_000001'; // sinh vien dau tien
        }
    
        $createStudent = Student::create([
            'StudentID' => $newStudentID,
            'HoTen' => $validateStudentInfo['HoTen'],
            'Email' => $validateStudentInfo['Email'],
            'Password' => bcrypt($validateStudentInfo['Password']),
            'GioiTinh' => $validateStudentInfo['GioiTinh'],
            'NamSinh' => $validateStudentInfo['NamSinh'],
            'DiemKinhNghiem' => 0.00,
            'Status' => '1',
            // 'StudentID' => $newStudentID,
            // 'HoTen' => $request->input('HoTen'),
            // 'Email' => $request->input('Email'),
            // 'Password' => bcrypt($request->input('Password')),
            // 'GioiTinh' => $request->input('GioiTinh'),
            // 'NamSinh' => $request->input('NamSinh'),
            // 'DiemKinhNghiem' => 0.00,
            // 'Status' => '1',
        ]);

        if (!$createStudent) {
            return response()->json([
                'message' => 'Khong luu duoc thong tin sinh vien',
            ], 500);
        }
    
        $token = $createStudent->createToken('api_token')->plainTextToken;
    
        return response()->json([
            'token' => $token,
            'student_info' => $createStudent,
        ], 201);
    }

    public function createLecturer(Request $request) {
        $thisYear = date('Y');
        $validateLecturerInfo = $request->validate([
            'HoTen' => 'required',
            'Email' => 'required|email|unique:students',
            'Password' => 'required|min:6',
            'GioiTinh' => 'required|in:0,1', // 0 là nam, 1 là nữ
            'NamSinh' => 'required|digits:4|integer|min:1975|max:'.$thisYear,
            'Status' => 'in:0,1', // 0 là bị ban, 1 là tài khoản oke
        ]);

    // Tu dong tao LecturerID
    $lastLecturer = Lecturer::orderBy('LecturerID', 'desc')->first();
    // $lecturerCount1 = Lecturer::count();
    $lecturerCount = 0;
    
        if ($lastLecturer) {
            $lastLecturerID = $lastLecturer->LecturerID;
            $lastNumber = (int) substr($lastLecturerID, 3);
            $lecturerCount = (int)$lastNumber;
            $newID = 'SV_' . str_pad($lecturerCount + 1, 6, '0', STR_PAD_LEFT);
        }
    
        // Generate new first lecturet ID
        else {
            $newID = 'LC_000001'; // khoi tao dau tien
        }
    
        $createLecturer = Lecturer::create([
            'LecturerID' => $newID,
            'HoTen' => $validateLecturerInfo['HoTen'],
            'Email' => $validateLecturerInfo['Email'],
            'Password' => bcrypt($validateLecturerInfo['Password']),
            'GioiTinh' => $validateLecturerInfo['GioiTinh'],
            'NamSinh' => $validateLecturerInfo['NamSinh'],
            'Status' => '1',
        ]);

        if (!$createLecturer) {
            return response()->json([
                'message' => 'Khong luu duoc thong tin giang vien',
            ], 500);
        }
    
        $token = $createLecturer->createToken('api_token')->plainTextToken;
    
        return response()->json([
            'token' => $token,
            'student_info' => $createLecturer,
        ], 201);
    }
}
