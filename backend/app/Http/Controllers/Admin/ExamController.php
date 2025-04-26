<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Exam;

class ExamController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware('isLecturer')->only(['createExam']); // chi cho phep admin thuc hien nhung ham nay
    }

    public function createExam(Request $request) { // tu dong tao de thi
        // Tu dong tao ExamID
        $lastExam = Exam::orderBy('ExamID', 'desc')->first();
        $getAdminEmail = auth()->user()->Email;
        // $studentCount1 = Student::count();
        $examCount = 0;
    
        if ($lastExam) {
            $lastExamID = $lastExam->ExamID;
            $lastNumber = (int) substr($lastExamID, 3);
            $examCount = (int)$lastNumber;
            $newExID = 'EX_' . str_pad($examCount + 1, 6, '0', STR_PAD_LEFT);
        }
    
        else {
            $newExID = 'EX_000001'; // exam dau tien
        }
    
        $createExam = Exam::create([
            'ExamID' => $newExID,
            'ExamName' => $request->input('ExamName'),
            'Status' => 1, // 1: available, 0: done
            'Author' => $getAdminEmail,
        ]);

        if (!$createExam) {
            return response()->json([
                'message' => 'Loi ket noi du lieu',
            ], 500);
        }
    
        return response()->json([
            'message' => 'Da hoan tat tao de thi',
        ], 200);
    }
}
